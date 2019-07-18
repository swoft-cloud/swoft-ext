<?php declare(strict_types=1);


namespace Swoft\Pgsql;

use function bean;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Pgsql\Connection\Connection;
use Swoft\Pgsql\Connection\PgsqlConnection;
use Swoft\Pgsql\Connector\PgsqlConnector;
use Swoft\Pgsql\Contract\ConnectorInterface;
use Swoft\Pgsql\Exception\PgsqlException;
use Swoft\Stdlib\Helper\Arr;

/**
 * Class PgsqlDb
 *
 * @since 2.0
 */
class PgsqlDb
{
    /**
     * Php pgsql
     */
    const PHP_PGSQL = 'pgsql';

    /**
     * @var string
     */
    private $driver = self::PHP_PGSQL;

    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 5432;

    /**
     * @var string
     */
    private $database = 'postgres';

    /**
     * @var array
     */
    private $schema = [];

    /**
     * @var string
     */
    private $user = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var float
     */
    private $timeout = 0.0;

    /**
     * @var int
     */
    private $retryInterval = 10;

    /**
     * @var int
     */
    private $readTimeout = 0;

    /**
     * @var array
     */
    private $connectors = [];

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @param Pool $pool
     *
     * @return Connection
     * @throws PgsqlException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function createConnection(Pool $pool): Connection
    {
        $connection = $this->getConnection();
        $connection->initialize($pool, $this);
        $connection->create();

        return $connection;
    }

    /**
     * @return ConnectorInterface
     * @throws PgsqlException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function getConnector(): ConnectorInterface
    {
        $connectors = Arr::merge($this->defaultConnectors(), $this->connectors);
        $connector  = $connectors[$this->driver] ?? null;

        if (!$connector instanceof ConnectorInterface) {
            throw new PgsqlException(sprintf('Connector(dirver=%s) is not exist', $this->driver));
        }

        return $connector;
    }

    /**
     * @return Connection
     * @throws PgsqlException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function getConnection(): Connection
    {
        $connections = Arr::merge($this->defaultConnections(), $this->connections);
        $connection  = $connections[$this->driver] ?? null;

        if (!$connection instanceof Connection) {
            throw new PgsqlException(sprintf('Connection(dirver=%s) is not exist', $this->driver));
        }

        return $connection;
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function defaultConnectors(): array
    {
        return [
            self::PHP_PGSQL => bean(PgsqlConnector::class)
        ];
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function defaultConnections(): array
    {
        return [
            self::PHP_PGSQL => bean(PgsqlConnection::class)
        ];
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
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
        return (int)$this->port;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return (string)$this->database;
    }

    /**
     * @return string
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return (string)$this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return (int)$this->timeout;
    }
    
    /**
     * @return int
     */
    public function getRetryInterval(): int
    {
        return (int)$this->retryInterval;
    }

    /**
     * @return int
     */
    public function getReadTimeout(): int
    {
        return (int)$this->readTimeout;
    }
}
