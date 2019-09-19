<?php declare(strict_types=1);


namespace Swoft\Swagger;

use Swoft\Swagger\Node\Info;

/**
 * Class OpenAPI
 *
 * @since 2.0
 */
class OpenAPI
{
    /**
     * @var string
     */
    protected $openapi = '3.0.0';

    /**
     * @var Info
     */
    protected $info;

    protected $servers = [];

    protected $paths = [];

    /**
     * @return array
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    /**
     * @param array $servers
     */
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }
}