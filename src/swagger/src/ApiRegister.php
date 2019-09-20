<?php declare(strict_types=1);


namespace Swoft\Swagger;


use Swoft\Swagger\Annotation\Mapping\ApiContact;
use Swoft\Swagger\Annotation\Mapping\ApiInfo;
use Swoft\Swagger\Annotation\Mapping\ApiLicense;
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
     * @var Server[]
     */
    private static $pathServers = [];

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

        self::$contract = new Contact($contact->getName(), $contact->getUrl(), $contact->getEmail());
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

        $title       = $info->getTitle();
        $desc        = $info->getDescription();
        $termService = $info->getTermsOfService();
        $version     = $info->getVersion();

        self::$info = new Info($title, $desc, $termService, $version);
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

        self::$license = new License($license->getName(), $license->getUrl());
    }

    /**
     * @param ApiServer $server
     */
    public static function registerServers(ApiServer $server): void
    {
        self::$servers[] = new Server($server->getUrl(), $server->getDescription());
    }

    /**
     * @param string    $className
     * @param string    $methodName
     * @param ApiServer $server
     */
    public static function registerPathServers(string $className, string $methodName, ApiServer $server): void
    {
        self::$pathServers[$className][$methodName] = new Server($server->getUrl(), $server->getDescription());
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
     * @return Server[]
     */
    public static function getPathServers(): array
    {
        return self::$pathServers;
    }
}