<?php declare(strict_types=1);


namespace Swoft\Swagger;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Swagger\Node\Info;
use Swoft\Swagger\Node\OpenApi;

/**
 * Class Swagger
 *
 * @since 2.0
 *
 * @Bean()
 */
class Swagger
{
    public function gen(): void
    {
        $openapi = $this->createRootNode();

        var_dump($openapi->toJson());
    }

    /**
     * @return OpenApi
     */
    private function createRootNode(): OpenApi
    {
        $info    = $this->createInfoNode();
        $servers = ApiRegister::getServers();

        $openApi = new OpenApi();
        $openApi->setInfo($info);
        $openApi->setServers($servers);

        return $openApi;
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
}