<?php declare(strict_types=1);


namespace Swoft\Swagger;


use Swoft\Swagger\Annotation\Mapping\ApiContact;
use Swoft\Swagger\Annotation\Mapping\ApiInfo;
use Swoft\Swagger\Annotation\Mapping\ApiLicense;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;
use Swoft\Swagger\Annotation\Mapping\ApiServer;
use Swoft\Swagger\Exception\SwaggerException;
use Swoft\Swagger\Node\Contact;
use Swoft\Swagger\Node\Info;
use Swoft\Swagger\Node\License;
use Swoft\Swagger\Node\Server;

class ApiRegister
{
    /**
     * @var Contact
     */
    private static $contract;

    /**
     * @var Info
     */
    private static $info;

    /**
     * @var License
     */
    private static $license;

    /**
     * @var Server[]
     */
    private static $servers = [];

    /**
     * @var array
     *
     * @example
     * [
     *     'schemaName' => [
     *         'className',
     *         ApiSchemaAnnotation
     *     ]
     * ]
     */
    private static $schemas = [];

    /**
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'propertyName' => ApiPropertyAnnotation|ApiPropertyEntityAnnotation|ApiPropertySchemaAnnotation
     *     ]
     * ]
     */
    private static $properties;

    /**
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *         'methodName' => [
     *              'operation' => ApiOperationAnnotation,
     *              'response' => ApiResponseAnnotation,
     *              'requestBody' => ApiRequestBodyAnnotation,
     *              'servers' => [
     *                  ApiServerAnnotation,
     *                  ApiServerAnnotation,
     *              ]
     *         ]
     *     ]
     * ]
     */
    private static $paths = [];


    /**
     * @param ApiContact $contact
     *
     * @throws SwaggerException
     */
    public static function registerContract(ApiContact $contact): void
    {
        if (!empty(self::$contract)) {
            throw new SwaggerException('`@ApiContact` annotation must be only one!');
        }

        $data = [
            'name'  => $contact->getName(),
            'url'   => $contact->getUrl(),
            'email' => $contact->getEmail()
        ];

        self::$contract = new Contact($data);
    }

    /**
     * @param ApiInfo $info
     *
     * @throws SwaggerException
     */
    public static function registerInfo(ApiInfo $info): void
    {
        if (!empty(self::$info)) {
            throw new SwaggerException('`@ApiInfo` annotation must be only one!');
        }

        $data       = [

            'title'          => $info->getTitle(),
            'description'    => $info->getDescription(),
            'termsOfService' => $info->getTermsOfService(),
            'version'        => $info->getVersion(),
        ];
        self::$info = new Info($data);
    }

    /**
     * @param ApiLicense $license
     *
     * @throws SwaggerException
     */
    public static function registerLicense(ApiLicense $license)
    {
        if (!empty(self::$license)) {
            throw new SwaggerException('`@ApiLicense` annotation must be only one!');
        }

        $data = [
            'name' => $license->getName(),
            'url'  => $license->getUrl()
        ];

        self::$license = new License($data);
    }

    /**
     * @param ApiServer $server
     */
    public static function registerServers(ApiServer $server): void
    {
        $data = [
            'url'         => $server->getUrl(),
            'description' => $server->getDescription()
        ];

        self::$servers[] = new Server($data);
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param object $annotation
     */
    public static function registerPaths(string $className, string $methodName, $annotation): void
    {
        if ($annotation instanceof ApiOperation) {
            self::$paths[$className][$methodName]['operation'] = $annotation;
            return;
        }

        if ($annotation instanceof ApiResponse) {
            self::$paths[$className][$methodName]['response'] = $annotation;
            return;
        }

        if ($annotation instanceof ApiRequestBody) {
            self::$paths[$className][$methodName]['requestBody'] = $annotation;
            return;
        }

        if ($annotation instanceof ApiServer) {
            self::$paths[$className][$methodName]['servers'][] = $annotation;
            return;
        }
    }

    /**
     * @param string    $className
     * @param ApiSchema $apiSchema
     */
    public static function registerSchema(string $className, ApiSchema $apiSchema): void
    {
        $schemaName    = $className;
        $apiSchemaName = $apiSchema->getName();
        if (!empty($apiSchemaName)) {
            $schemaName = $apiSchemaName;
        }

        self::$schemas[$schemaName] = [
            $className,
            $apiSchema
        ];
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param object $annotationObject
     */
    public static function registerProperty(string $className, string $propertyName, $annotationObject): void
    {
        self::$properties[$className][$propertyName] = $annotationObject;
    }

    /**
     * @return Contact
     */
    public static function getContract(): ?Contact
    {
        return self::$contract;
    }

    /**
     * @return Info
     */
    public static function getInfo(): ?Info
    {
        return self::$info;
    }

    /**
     * @return License
     */
    public static function getLicense(): ?License
    {
        return self::$license;
    }

    /**
     * @return Server[]
     */
    public static function getServers(): array
    {
        return self::$servers;
    }

    /**
     * @return array
     */
    public static function getSchemas(): array
    {
        return self::$schemas;
    }

    /**
     * @param string $schemaName
     *
     * @return array
     */
    public static function getSchemaByClassName(string $schemaName): array
    {
        return self::$schemas[$schemaName] ?? [];
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public static function getProperties(string $className): array
    {
        return self::$properties[$className] ?? [];
    }

    /**
     * @return array
     */
    public static function getPaths(): array
    {
        return self::$paths;
    }

    public static function checkPaths(): void
    {

    }
}