<?php

declare(strict_types=1);

namespace Swoft\Amqp;

use function bean;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Swoft\Amqp\Connection\Connection;
use Swoft\Amqp\Connection\SocketConnection;
use Swoft\Amqp\Connection\SSLConnection;
use Swoft\Amqp\Connection\StreamConnection;
use Swoft\Amqp\Connector\AMQPConnector;
use Swoft\Amqp\Contract\ConnectorInterface;
use Swoft\Amqp\Exception\AMQPException;
use Swoft\Stdlib\Helper\Arr;

/**
 * Class AmqpDb.
 *
 * @since   2.0
 */
class AmqpDb
{
    /**
     * StreamConnection.
     */
    const STREAM = 'StreamConnection';

    /**
     * SocketConnection.
     */
    const SOCKET = 'SocketConnection';

    /**
     * SSLConnection.
     */
    const SSL = 'SSLConnection';

    /**
     * @var string
     */
    private $driver = self::STREAM;

    /**
     * must contain keys (host, port, user, password, vhost).
     *
     * @var array
     */
    private $auths = [
        [
            'host' => '127.0.0.1',
            'port' => '5672',
            'user' => 'admin',
            'password' => 'admin',
            'vhost' => '/',
        ],
    ];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $exchange = [
        'name' => 'exchange',
        'type' => AMQPExchangeType::DIRECT,
        'passive' => false,
        'durable' => true,
        'auto_delete' => false,
        'internal' => false,
        'nowait' => false,
        'arguments' => [],
        'ticket' => null,
    ];

    /**
     * @var array
     */
    private $queue = [
        'name' => 'queue',
        'passive' => false,
        'durable' => true,
        'exclusive' => false,
        'auto_delete' => false,
        'nowait' => false,
        'arguments' => [],
        'ticket' => null,
    ];

    /**
     * @var array
     */
    private $route = [
        'key' => '',
        'nowait' => false,
        'arguments' => [],
        'ticket' => null,
    ];

    /**
     * @var array
     */
    private $consume = [
        'cancel_tag' => ['exit', 'quit'],
        'consumer_tag' => 'consumer',
        'no_local' => false,
        'no_ack' => false,
        'exclusive' => false,
        'nowait' => false,
    ];

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
     *
     * @throws AMQPException
     */
    public function createConnection(Pool $pool): Connection
    {
        $connection = $this->getConnection();
        $connection->initialize($pool, $this);
        $connection->createClient();

        return $connection;
    }

    /**
     * @return Connection
     *
     * @throws AMQPException
     */
    public function getConnection(): Connection
    {
        $connections = Arr::merge($this->defaultConnections(), $this->connections);
        $connection = $connections[$this->driver] ?? null;

        if (!$connection instanceof Connection) {
            throw new AMQPException(sprintf('Connection(dirver=%s) is not exist', $this->driver));
        }

        return $connection;
    }

    /**
     * @return ConnectorInterface
     *
     * @throws AMQPException
     */
    public function getConnector(): ConnectorInterface
    {
        $connectors = Arr::merge($this->defaultConnectors(), $this->connectors);
        $connector = $connectors[$this->driver] ?? null;

        if (!$connector instanceof ConnectorInterface) {
            throw new AMQPException(sprintf('Connector(dirver=%s) is not exist', $this->driver));
        }

        return $connector;
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function defaultConnectors(): array
    {
        return [
            self::STREAM => bean(AMQPConnector::class),
            self::SOCKET => bean(AMQPConnector::class),
            self::SSL => bean(AMQPConnector::class),
        ];
    }

    /**
     * @return array
     *
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function defaultConnections(): array
    {
        return [
            self::STREAM => bean(StreamConnection::class),
            self::SOCKET => bean(SocketConnection::class),
            self::SSL => bean(SSLConnection::class),
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
     * @return array
     */
    public function getAuths(): array
    {
        return $this->auths;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getExchange(): array
    {
        return $this->exchange;
    }

    /**
     * @return array
     */
    public function getQueue(): array
    {
        return $this->queue;
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getConsume(): array
    {
        return $this->consume;
    }
}
