<?php declare(strict_types=1);

namespace Swoft\Elasticsearch\Connection;

use Elasticsearch\ClientBuilder;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Connection\Pool\Contract\PoolInterface;
use Swoft\Elasticsearch\Client;

/**
 * Class Connection
 *
 * @since   2.0
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @package Swoft\Elasticsearch\Connection
 */
class Connection extends AbstractConnection
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @Inject()
     * @var ConnectionInstance
     */
    protected $instance;

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param PoolInterface $pool
     */
    public function setPool(PoolInterface $pool): void
    {
        $this->pool = $pool;
    }

    /**
     * @param int $lastTime
     */
    public function setLastTime(int $lastTime = null): void
    {
        if (is_null($lastTime)) {
            $lastTime = time();
        }
        $this->lastTime = $lastTime;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return ConnectionInstance
     */
    public function getInstance(): ConnectionInstance
    {
        return $this->instance;
    }

    /**
     * @param ConnectionInstance $instance
     */
    public function setInstance(ConnectionInstance $instance): void
    {
        $this->instance = $instance;
    }

    /**
     * create
     */
    public function create(): void
    {
        $driver  = $this->client->getDriver();
        $cloudId = $this->client->getCloudId();
        $ssl     = $this->client->getSsl();
        $retries = $this->client->getRetries();
        $user    = $this->client->getUser();
        $pass    = $this->client->getPass();
        $hosts   = [
            [
                'host'   => $this->client->getHost(),
                'port'   => $this->client->getPort(),
                'scheme' => $this->client->getScheme(),
                'path'   => $this->client->getPath(),
                'user'   => $user,
                'pass'   => $pass,
            ],
        ];

        $builder = ClientBuilder::create()->setRetries($retries);
        if (!empty($ssl)) {
            $builder->setSSLVerification($ssl);
        }
        switch ($driver) {
            default:
            case Client::DRIVER_DEFAULT:
                $builder->setHosts($hosts);
                break;
            case Client::DRIVER_BASIC:
                $builder->setElasticCloudId($cloudId)->setBasicAuthentication($user, $pass);
                break;
            case Client::DRIVER_SECRET:
                $builder->setElasticCloudId($cloudId)->setApiKey($user, $pass);
                break;
        }

        $elasticsearch = $builder->build();
        $this->instance->setElasticsearch($elasticsearch);
        $this->instance->setConnection($this);
    }

    /**
     * close
     */
    public function close(): void
    {
    }

    /**
     * reconnect
     *
     * @return bool
     */
    public function reconnect(): bool
    {
    }

    /**
     * release
     *
     * @param bool $force
     */
    public function release(bool $force = false): void
    {
        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $conManager->releaseConnection($this->id);

        parent::release($force);
    }

}
