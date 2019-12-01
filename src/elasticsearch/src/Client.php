<?php declare(strict_types=1);

namespace Swoft\Elasticsearch;

/**
 * Class Client
 *
 * @since   2.0
 *
 * @package Swoft\Elasticsearch
 */
class Client
{

    public const DRIVER_DEFAULT = 1;

    public const DRIVER_BASIC = 2;

    public const DRIVER_SECRET = 3;

    /**
     * @var int
     */
    private $driver = self::DRIVER_DEFAULT;

    /**
     * @var string
     */
    private $cloudId = '';

    /**
     * @var string
     */
    private $scheme = 'http';

    /**
     * @var string
     */
    private $host = 'localhost';

    /**
     * @var int
     */
    private $port = 9200;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $user = '';

    /**
     * @var string
     */
    private $pass = '';

    /**
     * @var int
     */
    private $retries = 0;

    /**
     * @var string
     */
    private $ssl = '';

    /**
     * @return int
     */
    public function getDriver(): int
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getCloudId(): string
    {
        return $this->cloudId;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @return int
     */
    public function getRetries(): int
    {
        return $this->retries;
    }

    /**
     * @return string
     */
    public function getSsl(): string
    {
        return $this->ssl;
    }
}
