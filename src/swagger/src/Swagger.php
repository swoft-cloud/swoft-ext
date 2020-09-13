<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger;

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
use Swoft\Swagger\Node\MediaType;
use Swoft\Swagger\Node\OpenApi;
use Swoft\Swagger\Node\Operation;
use Swoft\Swagger\Node\PathItem;
use Swoft\Swagger\Node\Property;
use Swoft\Swagger\Node\RequestBody;
use Swoft\Swagger\Node\Response;
use Swoft\Swagger\Node\Schema as SchemaNode;
use Swoft\Swagger\Node\Server;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\ValidateRegister;
use Swoft\Validator\ValidatorRegister;

/**
 * Class Swagger
 *
 * @since 2.0
 *
 * @Bean(name="swagger")
 */
class Swagger
{
    /**
     * JSON
     */
    public const JSON = 'json';

    /**
     * YAML
     */
    public const YAML = 'yaml';

    /**
     * @var SchemaNode[]
     */
    private $schemas = [];

    /**
     * @var array
     */
    private $dynamicSchemas = [];

    /**
     * @var array
     */
    private $schemaDefinitions = [];

    /**
     * @var string
     */
    private $type = self::JSON;

    /**
     * @var string
     */
    private $file = '@base/doc/swagger.json';

    /**
     * Init
     */
    public function init(): void
    {
        $this->schemaDefinitions = ApiRegister::getSchemas();
    }

    /**
     * @throws ReflectionException
     * @throws SwaggerException
     */
    public function gen(): void
    {
        $openapi = $this->createRootNode();
        $json    = $openapi->toJson();

        if ($this->type == self::JSON) {
            file_put_contents('/Users/stelin/swoft/swoft/resource/dist/doc/swagger.json', $json);
            return;
        }
    }

    /**
     * @return OpenApi
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
     * @return array
     * @throws ReflectionException
     * @throws SwaggerException
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

                $type   = $prop['type'];
                $hidden = $prop['hidden'];
                $prop   = $prop['pro'];

                // Default value
                $reflectProperty->setAccessible(true);
                $defultValue = $reflectProperty->getValue(new $className());

                // Hidden property
                if ($hidden) {
                    continue;
                }

                $propType = $this->transferPropertyType($type);

                // Array for entity will be to object
                if ($propType == 'array') {
                    $propType = 'object';
                }

                $propData = [
                    'type'        => $propType,
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

        return array_merge($schemas, $this->dynamicSchemas);
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
            // Property
            if ($propAnnotation instanceof ApiProperty) {
                $this->createProperty($className, $propertyName, $propAnnotation, $propNodes, $requireds);
                continue;
            }

            // Entity property
            if ($propAnnotation instanceof ApiPropertyEntity) {
                $this->createPropertyEntity($className, $propertyName, $propAnnotation, $propNodes, $requireds);
                continue;
            }

            // Schema property
            if ($propAnnotation instanceof ApiPropertySchema) {
                $this->createPropertySchema($className, $propertyName, $propAnnotation, $propNodes, $requireds);
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
     * @param string      $className
     * @param string      $propertyName
     * @param ApiProperty $propAnnotation
     * @param array       $propNodes
     * @param array       $requireds
     *
     * @return void
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createProperty(
        string $className,
        string $propertyName,
        ApiProperty $propAnnotation,
        array &$propNodes,
        array &$requireds
    ): void {
        // Parse php document
        $reflectProperty = new ReflectionProperty($className, $propertyName);
        $propType        = ObjectHelper::getPropertyBaseType($reflectProperty);
        $defultValue     = $reflectProperty->getValue(new $className());
        $propType        = $this->transferPropertyType($propType);

        $propData = [
            'type'        => $propType,
            'description' => $propAnnotation->getDescription()
        ];

        if ($defultValue !== null) {
            $propData['default'] = $defultValue;
        }

        $name        = $propAnnotation->getName();
        $swgPropName = $propertyName;
        if (!empty($name)) {
            $swgPropName = $name;
        }

        if ($propAnnotation->isRequired()) {
            $requireds[] = $swgPropName;
        }

        $propNodes[$swgPropName] = new Property($propData);
    }

    /**
     * @param string            $className
     * @param string            $propertyName
     * @param ApiPropertySchema $propAnnotation
     * @param array             $propNodes
     * @param array             $requireds
     *
     * @return void
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createPropertySchema(
        string $className,
        string $propertyName,
        ApiPropertySchema $propAnnotation,
        array &$propNodes,
        array &$requireds
    ): void {
        $refSchemaName = $propAnnotation->getSchema();
        $fields        = $propAnnotation->getFields();
        $unfields      = $propAnnotation->getUnfields();

        $name = $propAnnotation->getName();

        if ($propAnnotation->isRequired()) {
            $requireds[] = $name;
        }

        $type = $propAnnotation->getType();
        $desc = $propAnnotation->getDescription();

        $schema = $this->createDynamicProperty($type, $desc, $refSchemaName, $fields, $unfields);

        // Schema node
        $propNodes[$name] = $schema;
    }

    /**
     * @param string            $className
     * @param string            $propertyName
     * @param ApiPropertyEntity $propAnnotation
     * @param array             $propNodes
     * @param array             $requireds
     *
     * @return void
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createPropertyEntity(
        string $className,
        string $propertyName,
        ApiPropertyEntity $propAnnotation,
        array &$propNodes,
        array &$requireds
    ): void {
        $refSchemaName = $propAnnotation->getEntity();
        $fields        = $propAnnotation->getFields();
        $unfields      = $propAnnotation->getUnfields();

        $name = $propAnnotation->getName();

        if ($propAnnotation->isRequired()) {
            $requireds[] = $name;
        }

        $type = $propAnnotation->getType();
        $desc = $propAnnotation->getDescription();

        $schema = $this->createDynamicProperty($type, $desc, $refSchemaName, $fields, $unfields);

        // Schema node
        $propNodes[$name] = $schema;
    }

    /**
     * @param string $type
     * @param string $description
     * @param string $refSchemaName
     * @param array  $fields
     * @param array  $unfields
     *
     * @return SchemaNode
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createDynamicProperty(
        string $type,
        string $description,
        string $refSchemaName,
        array $fields,
        array $unfields
    ): SchemaNode {
        $refNewProps       = [];
        $refSchema         = $this->createSchema($refSchemaName);
        $refSchemaProps    = $refSchema->getProperties();
        $refSchemaRequired = $refSchema->getRequired();
        foreach ($refSchemaProps as $refSchemaPropName => $refSchemaProp) {
            $isNotFields = !empty($fields) && !in_array($refSchemaPropName, $fields);
            $isUnfields  = !empty($unfields) && in_array($refSchemaPropName, $unfields);
            if ($isNotFields || $isUnfields) {
                ArrayHelper::remove($refSchemaRequired, $refSchemaPropName);
                continue;
            }

            $refNewProps[$refSchemaPropName] = $refSchemaProp;
        }

        $propData = [
            'type'        => $type,
            'properties'  => $refNewProps,
            'required'    => $refSchemaRequired,
            'description' => $description
        ];

        return new SchemaNode($propData);
    }

    /**
     * @param string $type
     *
     * @return string
     * @throws SwaggerException
     */
    private function transferPropertyType(string $type): string
    {
        $mapping = [
            'array'   => 'array',
            'bool'    => 'boolean',
            'boolean' => 'boolean',
            'int'     => 'integer',
            'integer' => 'integer',
            'object'  => 'object',
            'string'  => 'string',
            'float'   => 'number',
            'double'  => 'number',
            'number'  => 'number'
        ];

        if (!isset($mapping[$type])) {
            throw new SwaggerException(
                sprintf('%s type is not var type', $type)
            );
        }

        return $mapping[$type];
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
     * @return array
     * @throws SwaggerException
     */
    private function createPaths(): array
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
                        'The %s of %s must be define `@RequestMapping`',
                        $className,
                        $methodName
                    ));
                }

                $pathRouter        = $handlerRoutes[$pathHandler];
                $routeHandlers     = $pathRouter['handlers'];
                $routePath         = $pathRouter['path'];
                $paths[$routePath] = $this->createPathItem($path, $routeHandlers);
            }
        }

        return $paths;
    }

    /**
     * @param array $path
     * @param array $routeHandlers
     *
     * @return PathItem
     * @throws SwaggerException
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
     * @throws SwaggerException
     */
    private function createOperation(array $path, array $handler): Operation
    {
        $notRbMethods = [
            'GET'
        ];

        /* @var ApiOperation $operation */
        $operation = $path['operation'];

        /* @var ApiResponse[] $responses */
        $responses = $path['response'];

        /* @var ApiRequestBody[] $requestBodys */
        $requestBodys = $path['requestBody'];

        /* @var ApiServer[] $servers */
        $servers = $path['servers'] ?? [];

        $handlerKey = $handler['handler'];
        [$className, $methodName] = explode('@', $handlerKey);


        $method = $handler['method'];
        $method = strtoupper($method);

        $serverNodes = [];
        foreach ($servers as $server) {
            $serverInfo = [
                'url'         => $server->getUrl(),
                'description' => $server->getDescription()
            ];

            $serverNodes[] = new Server($serverInfo);
        }

        // Create response node
        $responses = $this->createResponses($responses);

        $data = [
            'tags'        => $operation->getTags(),
            'summary'     => $operation->getSummary(),
            'description' => $operation->getDescription(),
            'operationId' => $operation->getOperationId(),
            'servers'     => $serverNodes,
            'responses'   => $responses,
        ];

        // Add Request body
        if (!in_array($method, $notRbMethods)) {
            $data['requestBody'] = $this->createRequestBody($className, $methodName, $requestBodys);
        }

        return new Operation($data);
    }

    /**
     * @param string           $className
     * @param string           $methodName
     * @param ApiRequestBody[] $requestBodys
     *
     * @return RequestBody
     * @throws SwaggerException
     */
    private function createRequestBody(string $className, string $methodName, array $requestBodys): RequestBody
    {
        $mediaTypes  = [];
        $description = '';
        $required    = false;
        foreach ($requestBodys as $requestBody) {
            $schema      = $requestBody->getSchema();
            $description = $requestBody->getDescription();
            $required    = $requestBody->isRequired();
            $contentType = $requestBody->getContentType();
            if (!empty($schema)) {
                $mediaData = [
                    'schema' => [
                        '$ref' => sprintf('#/components/schemas/%s', $schema)
                    ]
                ];
            } else {
                $mediaData = [
                    'schema' => $this->createRequestBodySchema($className, $methodName)
                ];
            }

            $mediaTypes[$contentType] = new MediaType($mediaData);
        }

        $requestData = [
            'description' => $description,
            'content'     => $mediaTypes,
            'required'    => $required,
        ];

        return new RequestBody($requestData);
    }

    /**
     * @param string $className
     * @param string $method
     *
     * @return SchemaNode
     * @throws ReflectionException
     * @throws SwaggerException
     */
    private function createRequestBodySchema(string $className, string $method): SchemaNode
    {
        $required      = [];
        $propertyNodes = [];
        $validates     = ValidateRegister::getValidates($className, $method);
        foreach ($validates as $validatorName => $validate) {
            $fields        = $validate['fields'] ?? [];
            $unfields      = $validate['unfields'] ?? [];
            $validator     = ValidatorRegister::getValidator($validatorName);
            $properties    = $validator['properties'] ?? [];
            $propClassName = $validator['class'];

            foreach ($properties as $propName => $property) {
                $default  = $property['type']['default'] ?? null;
                $propAnno = $property['type']['annotation'];

                $notFields  = !empty($fields) && !in_array($propName, $fields);
                $isUnfields = !empty($unfields) && in_array($propName, $unfields);
                if ($notFields || $isUnfields) {
                    continue;
                }

                $propData = [
                    'default'     => $default,
                    'type'        => $this->transferValidatorType($propAnno),
                    'description' => DocBlock::getPropertyDescription($propClassName, $propName)
                ];

                $propertyNodes[$propName] = new Property($propData);
            }
        }

        $data = [
            'required'   => $required,
            'properties' => $propertyNodes
        ];

        return new SchemaNode($data);
    }

    /**
     * @param $annotation
     *
     * @return string
     * @throws SwaggerException
     */
    private function transferValidatorType($annotation): string
    {
        if ($annotation instanceof IsString) {
            return 'string';
        }

        if ($annotation instanceof IsInt) {
            return 'integer';
        }

        if ($annotation instanceof IsArray) {
            return 'array';
        }

        if ($annotation instanceof IsBool) {
            return 'boolean';
        }

        if ($annotation instanceof IsFloat) {
            return 'number';
        }

        throw new SwaggerException(
            sprintf('%s validate type is not supported!', get_class($annotation))
        );
    }

    /**
     * @param ApiResponse[] $responses
     *
     * @return array
     */
    private function createResponses(array $responses): array
    {
        $mediaTypes    = [];
        $responseNodes = [];
        foreach ($responses as $response) {
            $status      = $response->getStatus();
            $contentType = $response->getContentType();
            $schema      = $response->getSchema();
            $description = $response->getDescription();
            $charset     = $response->getCharset();

            $mediaData = [
                'schema' => [
                    '$ref' => sprintf('#/components/schemas/%s', $schema)
                ]
            ];

            // Add charset
            $contentType = sprintf('%s;charset=%s', $contentType, $charset);

            // MediaTypes
            $mediaTypes[$contentType] = new MediaType($mediaData);

            $data = [
                'description' => $description,
                'content'     => $mediaTypes
            ];

            $responseNodes[$status] = new Response($data);
        }

        return $responseNodes;
    }
}
