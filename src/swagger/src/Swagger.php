<?php declare(strict_types=1);


namespace Swoft\Swagger;

use PhpDocReader\AnnotationException;
use PhpDocReader\PhpDocReader;
use ReflectionException;
use ReflectionProperty;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\EntityRegister;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\DocBlock;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\Annotation\Mapping\ApiProperty;
use Swoft\Swagger\Annotation\Mapping\ApiPropertyEntity;
use Swoft\Swagger\Annotation\Mapping\ApiPropertySchema;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;
use Swoft\Swagger\Annotation\Mapping\ApiServer;
use Swoft\Swagger\Exception\SwaggerException;
use Swoft\Swagger\Node\Components;
use Swoft\Swagger\Node\Info;
use Swoft\Swagger\Node\OpenApi;
use Swoft\Swagger\Node\Operation;
use Swoft\Swagger\Node\PathItem;
use Swoft\Swagger\Node\Paths;
use Swoft\Swagger\Node\Property;
use Swoft\Swagger\Node\Schema as SchemaNode;
use Swoft\Swagger\Node\Server;

/**
 * Class Swagger
 *
 * @since 2.0
 *
 * @Bean()
 */
class Swagger
{
    /**
     * @var SchemaNode[]
     */
    private $schemas = [];

    /**
     * @var array
     */
    private $schemaDefinitions = [];

    public function init(): void
    {
        $this->schemaDefinitions = ApiRegister::getSchemas();
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    public function gen(): void
    {
        $openapi = $this->createRootNode();

        var_dump($openapi->toJson());
    }

    /**
     * @return OpenApi
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createRootNode(): OpenApi
    {
        $info       = $this->createInfoNode();
        $servers    = ApiRegister::getServers();
        $paths      = $this->createPaths();
        $components = $this->createComponentsNode();

        $openApi = new OpenApi();
        $openApi->setInfo($info);
        $openApi->setServers($servers);
        $openApi->setPaths($paths);
        $openApi->setComponents($components);

        return $openApi;
    }

    /**
     * @return Components
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createComponentsNode(): Components
    {
        $schemas    = $this->createSchemas();
        $components = new Components([
            'schemas' => $schemas
        ]);

        return $components;
    }

    /**
     * @return array
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createSchemas(): array
    {
        $schemas = [];
        foreach ($this->schemaDefinitions as $schemaName => $classSchema) {
            $schemas[$schemaName] = $this->createSchema($schemaName);
        }

        return $schemas;
    }

    /**
     * @param string $schemaName
     *
     * @return SchemaNode
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createSchema(string $schemaName): SchemaNode
    {
        if (isset($this->schemas[$schemaName])) {
            return $this->schemas[$schemaName];
        }

        if (!isset($this->schemaDefinitions[$schemaName])) {
            throw new SwaggerException(
                sprintf('%s schema is not defined', $schemaName)
            );
        }

        $schemaDefinition = $this->schemaDefinitions[$schemaName];

        /* @var ApiSchema $schemaAnnotation */
        [$className] = $schemaDefinition;
        $properties = ApiRegister::getProperties($className);

        $propNodes = [];
        $requireds = [];
        foreach ($properties as $propertyName => $propAnnotation) {

            $propData = [];
            if ($propAnnotation instanceof ApiProperty) {
                if ($propAnnotation->isRequired()) {
                    $requireds[] = $propertyName;
                }

                // Parse php document
                $reflectProperty = new ReflectionProperty($className, $propertyName);
                $propType        = ObjectHelper::getPropertyBaseType($reflectProperty);
                $description     = DocBlock::description($reflectProperty->getDocComment());
                $defultValue     = $reflectProperty->getValue(new $className());

                $propData = [
                    'type'        => $propType,
                    'description' => $description
                ];

                if ($defultValue !== null) {
                    $propData['default'] = $defultValue;
                }

            } elseif ($propAnnotation instanceof ApiPropertyEntity) {

            } elseif ($propAnnotation instanceof ApiPropertySchema) {
                $refSchemaName = $propAnnotation->getName();

                // Parse php document
                $phpReader       = new PhpDocReader();
                $reflectProperty = new ReflectionProperty($className, $propertyName);
                $refClassName    = $phpReader->getPropertyClass($reflectProperty);
                $description     = DocBlock::description($reflectProperty->getDocComment());

                if (empty($refSchemaName)) {
                    $refSchemaName = $refClassName;
                }


                $propData = [
                    'type'        => $propAnnotation->getType(),
                    'ref'         => sprintf('#/components/schemas/%s', $refSchemaName),
                    'description' => $description
                ];
            } else {

            }

            $propNodes[$propertyName] = new Property($propData);
        }

        $data = [
            'properties' => $propNodes,
            'required'   => $requireds
        ];

        $schema = new SchemaNode($data);

        $this->schemas[$schemaName] = $schema;
        return $schema;
    }

    /**
     * @param string            $className
     * @param string            $propName
     * @param ApiPropertySchema $propAnt
     * @param string            $refSchemaName
     *
     * @return string
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createDynamicSchema(
        string $className,
        string $propName,
        ApiPropertySchema $propAnt,
        string $refSchemaName
    ): string {
        $apiSchemaFields   = $propAnt->getFields();
        $apiSchemaUnfields = $propAnt->getUnfields();

        $refSchemaName = ApiRegister::getSchemaName($refSchemaName);
        $refSchema     = $this->createSchema($refSchemaName);

        $newProps     = [];
        $newRequireds = [];

        // Dynamic to generate schema
        $refSchemaProps = $refSchema->getProperties();
        foreach ($refSchemaProps as $refSchemaPropName => $refSchemaProp) {
            $isFields   = empty($apiSchemaFields) || in_array($refSchemaPropName, $apiSchemaFields);
            $isUnfields = empty($apiSchemaUnfields) || !in_array($refSchemaPropName, $apiSchemaUnfields);

            if ($isFields && $isUnfields) {
                $newProps[$refSchemaPropName] = $refSchemaProp;
            } else {
                ArrayHelper::remove($newRequireds, $refSchemaProp);
            }
        }

        $data = [
            'properties' => $newProps,
            'required'   => $newRequireds
        ];


        $schema = new SchemaNode($data);

        $schemaName = sprintf('%s_%s_%s', $refSchemaName, md5($className), $propName);

        $this->schemas[$schemaName] = $schema;
    }


    /**
     * @return Info
     */
    private function createInfoNode(): Info
    {
        $contract = ApiRegister::getContract();
        $license  = ApiRegister::getLicense();

        $info = ApiRegister::getInfo();
        $info->setContact($contract);
        $info->setLicense($license);

        return $info;
    }

    /**
     * @return Paths
     * @throws SwaggerException
     */
    private function createPaths(): Paths
    {
        /** @var Router $router Register HTTP routes */
        $router = bean('httpRouter');
        $routes = $router->getRoutes();

        $handlerRoutes = [];
        foreach ($routes as $route) {
            $handler                               = $route['handler'];
            $handlerRoutes[$handler]['handlers'][] = $route;
            $handlerRoutes[$handler]['path']       = $route['path'];

        }

        $paths         = [];
        $registerPaths = ApiRegister::getPaths();
        foreach ($registerPaths as $className => $methodPaths) {
            foreach ($methodPaths as $methodName => $path) {
                $pathHandler = sprintf('%s@%s', $className, $methodName);
                if (!isset($handlerRoutes[$pathHandler])) {
                    throw new SwaggerException(sprintf(
                        'The %s of %s must be define `@RequestMapping`', $className, $methodName
                    ));
                }

                $pathRouter        = $handlerRoutes[$pathHandler];
                $routeHandlers     = $pathRouter['handlers'];
                $routePath         = $pathRouter['path'];
                $paths[$routePath] = $this->createPathItem($path, $routeHandlers);
            }
        }

        return new Paths($paths);
    }

    /**
     * @param array $path
     * @param array $routeHandlers
     *
     * @return PathItem
     */
    private function createPathItem(array $path, array $routeHandlers): PathItem
    {
        $data = [];
        foreach ($routeHandlers as $handler) {
            $method = $handler['method'];
            $method = strtolower($method);

            $data[$method] = $this->createOperation($path, $handler);
        }

        return new PathItem($data);
    }

    /**
     * @param array $path
     * @param array $handler
     *
     * @return Operation
     */
    private function createOperation(array $path, array $handler): Operation
    {
        /* @var ApiOperation $operation */
        $operation = $path['operation'];

        /* @var ApiResponse $response */
        $response = $path['response'];

        /* @var ApiRequestBody $requestBody */
        $requestBody = $path['requestBody'];

        /* @var ApiServer[] $servers */
        $servers = $path['servers'] ?? [];

        $method = $handler['method'];
        $method = strtolower($method);

        $serverNodes = [];
        foreach ($servers as $server) {
            $serverInfo = [
                'url'         => $server->getUrl(),
                'description' => $server->getDescription()
            ];

            $serverNodes[] = new Server($serverInfo);
        }

        $data = [
            'tags'        => $operation->getTags(),
            'summary'     => $operation->getSummary(),
            'description' => $operation->getDescription(),
            'operationId' => $operation->getOperationId(),
            'servers'     => $serverNodes
        ];

        return new Operation($data);
    }
}