<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Amqp;

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
 * Class Client
 *
 * @since 2.0
 */
class Client
{
    /**
     * StreamConnection
     */
    const STREAM = 'StreamConnection';

    /**
     * SocketConnection
     */
    const SOCKET = 'SocketConnection';

    /**
     * SSLConnection
     */
    const SSL = 'SSLConnection';

    /**
     * @var string
     */
    private $driver = self::STREAM;

    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 5672;

    /**
     * @var string
     */
    private $user = 'admin';

    /**
     * @var string
     */
    private $password = 'admin';

    /**
     * @var string
     */
    private $vhost = '/';

    /**
     * @var string
     */
    private $type = AMQPExchangeType::TOPIC;

    /**
     * @var array
     */
    private $channels = [];

    /**
     * @var string
     */
    private $exchange = '';

    /**
     * @var string
     */
    private $queue = '';

    /**
     * @var string
     */
    private $route = '';

    /**
     * @var array
     */
    private $setting = [
        'passive'     => false,
        'durable'     => true,
        'exclusive'   => false,
        'auto_delete' => false,
        'nowait'      => false,
        'ticket'      => null,
        'arguments'   => [],
        'consume'     => [
            'consumer_tag' => '',
            'no_local'     => false,
            'no_ack'       => false,
            'exclusive'    => false,
            'nowait'       => false,
        ],
        'connect'     => [],
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
     * @throws AMQPException
     */
    public function getConnection(): Connection
    {
        $connections = Arr::merge($this->defaultConnections(), $this->connections);
        $connection  = $connections[$this->driver] ?? null;

        if (!$connection instanceof Connection) {
            throw new AMQPException(sprintf('Connection(dirver=%s) is not exist', $this->driver));
        }

        return $connection;
    }

    /**
     * @return ConnectorInterface
     * @throws AMQPException
     */
    public function getConnector(): ConnectorInterface
    {
        $connectors = Arr::merge($this->defaultConnectors(), $this->connectors);
        $connector  = $connectors[$this->driver] ?? null;

        if (!$connector instanceof ConnectorInterface) {
            throw new AMQPException(sprintf('Connector(dirver=%s) is not exist', $this->driver));
        }

        return $connector;
    }

    /**
     * @return array
     */
    public function defaultConnectors(): array
    {
        return [
            self::STREAM => bean(AMQPConnector::class),
            self::SOCKET => bean(AMQPConnector::class),
            self::SSL    => bean(AMQPConnector::class),
        ];
    }

    /**
     * @return array
     */
    public function defaultConnections(): array
    {
        return [
            self::STREAM => bean(StreamConnection::class),
            self::SOCKET => bean(SocketConnection::class),
            self::SSL    => bean(SSLConnection::class),
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
        return $this->port;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getVhost(): string
    {
        return $this->vhost;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @param string $exchange
     */
    public function setExchange(string $exchange): void
    {
        $this->exchange = $exchange;
    }

    /**
     * @param string $queue
     */
    public function setQueue(string $queue): void
    {
        $this->queue = $queue;
    }

    /**
     * @param array $channels
     */
    public function setChannels(array $channels): void
    {
        $this->channels = $channels;
    }
}
