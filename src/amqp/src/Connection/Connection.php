<?php declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use Closure;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPSocketConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Amqp\AmqpDb;
use Swoft\Amqp\Contract\ConnectionInterface;
use Swoft\Amqp\Exception\AMQPException;
use Swoft\Amqp\Pool;
use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractConnection;

/**
 * Class Connection
 *
 * @since   2.0
 *
 * @package Swoft\Amqp\Connection
 */
class Connection extends AbstractConnection implements ConnectionInterface
{
    /**
     * @var AMQPSSLConnection | AMQPSocketConnection | AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $exchange;

    /**
     * @var string
     */
    protected $queue;

    /**
     * @var AmqpDb
     */
    protected $amqpDb;

    /**
     * @param Pool   $pool
     * @param AmqpDb $redisDb
     */
    public function initialize(Pool $pool, AmqpDb $amqpDb)
    {
        $this->pool     = $pool;
        $this->amqpDb   = $amqpDb;
        $this->lastTime = time();

        $this->id = $this->pool->getConnectionId();
    }

    /**
     * create
     *
     * @throws AMQPException
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function create(): void
    {
        $this->createClient();
    }

    /**
     * createClient
     *
     * @throws AMQPException
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function createClient(): void
    {
        $auths    = $this->amqpDb->getAuths();
        $options  = $this->amqpDb->getOptions();
        $exchange = $this->amqpDb->getExchange();
        $queue    = $this->amqpDb->getQueue();
        $route    = $this->amqpDb->getRoute();
        try {
            $this->connection = $this->connect($auths, $options);
            $this->channel    = $this->connection->channel();
        } catch (Exception $e) {
            throw new AMQPException(
                sprintf('RabbitMQ connect error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        $this->declareQueue($queue);
        $this->declareExchange($exchange);
        $this->bind($route);
    }

    /**
     * reconnect
     *
     * @return bool
     * @throws AMQPException
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function reconnect(): bool
    {
        try {
            $this->createClient();
        } catch (Throwable $e) {
            Log::error('RabbitMQ reconnect error(%s)', $e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 关闭连接
     * close
     *
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
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

    /**
     * 声明交换器
     * declareExchange
     *
     * @param array $exchange
     *
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function declareExchange(array $exchange): void
    {
        try {
            $this->channel->exchange_declare(
                $exchange['name'],
                $exchange['type'],
                $exchange['passive'] ?? false,
                $exchange['durable'] ?? true,
                $exchange['auto_delete'] ?? false,
                $exchange['internal'] ?? false,
                $exchange['nowait'] ?? false,
                $exchange['arguments'] ?? [],
                $exchange['ticket'] ?? null
            );
            $this->exchange = $exchange['name'];
        } catch (Exception $exception) {
            throw new AMQPException(
                sprintf('RabbitMQ declare exchange error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
    }

    /**
     * 声明队列
     * declareQueue
     *
     * @param array $queue
     *
     * @throws AMQPException
     */
    public function declareQueue(array $queue): void
    {
        try {
            $this->channel->queue_declare(
                $queue['name'],
                $queue['passive'] ?? false,
                $queue['durable'] ?? true,
                $queue['exclusive'] ?? false,
                $queue['auto_delete'] ?? false,
                $queue['nowait'] ?? false,
                $queue['arguments'] ?? [],
                $queue['ticket'] ?? null
            );
            $this->queue = $queue['name'];
        } catch (Exception $exception) {
            throw new AMQPException(
                sprintf('RabbitMQ declare queue error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
    }

    /**
     * 绑定队列和交换器
     * bind
     *
     * @param array $route
     *
     * @throws AMQPException
     */
    public function bind(array $route): void
    {
        try {
            $this->channel->queue_bind(
                $this->queue,
                $this->exchange,
                $config['key'] ?? '',
                $config['nowait'] ?? false,
                $config['arguments'] ?? [],
                $config['ticket'] ?? null
            );
        } catch (Exception $exception) {
            throw new AMQPException(
                sprintf('RabbitMQ bind queue and exchange error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }
    }

    /**
     * 推送消息
     * push
     *
     * @param string $body
     * @param array  $properties
     * @param string $routeKey
     *
     * @throws \PhpAmqpLib\Exception\AMQPConnectionClosedException
     */
    public function push(string $message, array $prop = [], string $route = ''): void
    {
        $body = new AMQPMessage($message, $prop);
        $this->channel->basic_publish($body, $this->exchange, $route);
        $this->release();
    }

    /**
     * 获取第一条消息
     * pop
     *
     * @return string|null
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function pop(): ?string
    {
        /* @var AMQPMessage $message */
        $message = $this->channel->basic_get($this->queue, true);
        $this->release();

        return $message ? $message->body : null;
    }

    /**
     * 持续订阅消息
     * consume
     *
     * @param Closure|null $callback
     * @param array        $consume
     *
     * @return string
     * @throws \ErrorException
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     * @throws \PhpAmqpLib\Exception\AMQPRuntimeException
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     */
    public function consume(Closure $callback = null): void
    {
        //消费消息
        $consume = $this->amqpDb->getConsume();
        $this->channel->basic_consume(
            $this->queue,
            $consume['consumer_tag'] ?? '',
            $consume['no_local'] ?? false,
            $consume['no_ack'] ?? false,
            $consume['exclusive'] ?? false,
            $consume['nowait'] ?? false,
            function (AMQPMessage $message) use ($callback, $consume) {
                $cancel = is_array($consume['cancel_tag']) ? in_array($message->body, $consume['cancel_tag']) : $message->body == $consume['cancel_tag'];
                $this->channel->basic_ack($message->delivery_info['delivery_tag']);
                if ($cancel) {
                    $this->channel->basic_cancel($message->delivery_info['consumer_tag']);
                }
                !empty($callback) && $callback($message);
            }
        );
        //等待获取队列
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->release();
    }
}