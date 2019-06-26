<?php declare(strict_types=1);


namespace Swoft\Consul;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Consul
 *
 * @since 2.0
 *
 * @Bean()
 */
class Consul
{
    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 8500;

    public function get($url = null, array $options = array())
    {
        return $this->doRequest('GET', $url, $options);
    }

    public function head($url, array $options = array())
    {
        return $this->doRequest('HEAD', $url, $options);
    }

    public function delete($url, array $options = array())
    {
        return $this->doRequest('DELETE', $url, $options);
    }

    public function put($url, array $options = array())
    {
        return $this->doRequest('PUT', $url, $options);
    }

    public function patch($url, array $options = array())
    {
        return $this->doRequest('PATCH', $url, $options);
    }

    public function post($url, array $options = array())
    {
        return $this->doRequest('POST', $url, $options);
    }

    public function options($url, array $options = array())
    {
        return $this->doRequest('OPTIONS', $url, $options);
    }
}