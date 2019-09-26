<?php declare(strict_types=1);


namespace Swoft\Swagger;

use PhpDocReader\AnnotationException;
use PhpDocReader\PhpDocReader;
use ReflectionException;
use ReflectionProperty;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Annotation\Mapping\Entity;
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

    /**
     * Init
     */
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
        $eEntity = $this->createEntitySchema();
        $schemas = $this->createSchemas();

        $components = new Components([
            'schemas' => array_merge($eEntity, $schemas)
        ]);

        return $components;
    }

    /**
     * @throws ReflectionException
     */
    private function createEntitySchema(): array
    {
        $schemas = [];
        $columns = EntityRegister::getColumns();
        foreach ($columns as $className => $propColumns) {

            $propNodes   = [];
            $propMapping = $propColumns['mapping'] ?? [];
            foreach ($propMapping as $propName => $prop) {
                // Parse php document
                $reflectProperty = new ReflectionProperty($className, $propName);
                $description     = DocBlock::description($reflectProperty->getDocComment());

                $type        = $prop['type'];
                $hidden      = $prop['hidden'];
                $prop        = $prop['pro'];

                // Default value
                $reflectProperty->setAccessible(true);
                $defultValue = $reflectProperty->getValue(new $className());

                // Hidden property
                if ($hidden) {
                    continue;
                }

                $propData = [
                    'type'        => $this->transferPropertyType($type),
                    'description' => $description,
                ];

                if ($defultValue !== null) {
                    $propData['default'] = $defultValue;
                }

                $propNodes[$prop] = new Property($propData);
            }

            $data = [
                'properties' => $propNodes,
            ];

            $schema     = new SchemaNode($data);
            $schemaName = ApiRegister::getSchemaName($className);

            $schemas[$schemaName]       = $schema;
            $this->schemas[$schemaName] = $schema;
        }

        return $schemas;
    }

    /**
     * @return array
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
            $propData    = [];
            $swgPropName = $propertyName;

            // Property
            if ($propAnnotation instanceof ApiProperty) {
                if ($propAnnotation->isRequired()) {
                    $requireds[] = $propertyName;
                }

                // Parse php document
                $reflectProperty = new ReflectionProperty($className, $propertyName);
                $propType        = ObjectHelper::getPropertyBaseType($reflectProperty);
                $defultValue     = $reflectProperty->getValue(new $className());

                $propData = [
                    'type'        => $propType,
                    'description' => $propAnnotation->getDescription()
                ];

                if ($defultValue !== null) {
                    $propData['default'] = $defultValue;
                }

                $name = $propAnnotation->getName();
                if (!empty($name)) {
                    $swgPropName = $name;
                }

                $propNodes[$swgPropName] = new Property($propData);
                continue;
            }

            // Entity property
            if ($propAnnotation instanceof ApiPropertyEntity) {

                $name = $propAnnotation->getName();
                if (!empty($name)) {
                    $swgPropName = $name;
                }

                $propNodes[$swgPropName] = new Property($propData);
                continue;
            }

            // Schema property
            if ($propAnnotation instanceof ApiPropertySchema) {
                $refSchemaName = $propAnnotation->getSchema();

                $propData = [
                    'type'        => $propAnnotation->getType(),
                    'ref'         => sprintf('#/components/schemas/%s', $refSchemaName),
                    'description' => $propAnnotation->getDescription()
                ];

                $name = $propAnnotation->getName();
                if (!empty($name)) {
                    $swgPropName = $name;
                }

                $propNodes[$swgPropName] = new Property($propData);
                continue;
            }
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
     * @param string $type
     *
     * @return string
     */
    private function transferPropertyType(string $type): string
    {
        return $type;
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