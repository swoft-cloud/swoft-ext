<?php

declare(strict_types=1);

namespace Swoft\Amqp;

use Swoft\Amqp\Connection\Connection;
use Swoft\Amqp\Connection\ConnectionManager;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Swoft\Amqp\Exception\AMQPException;
use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractPool;
use Throwable;

/**
 * Class Pool.
 *
 * @since   2.0
 */
class Pool extends AbstractPool
{
    /**
     * Default pool.
     */
    const DEFAULT_POOL = 'amqp.pool';

    /**
     * @var AmqpDb
     */
    protected $amqpDb;

    /**
     * @return ConnectionInterface
     *
     * @throws Exception\RedisException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->amqpDb->createConnection($this);
    }

    /**
     * call magic method.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Connection
     *
     * @throws RedisException
     */
    public function __call(string $name, array $arguments)
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            $connection = $this->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new AMQPException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new RedisException(
                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
            );
        }

        return $connection->{$name}(...$arguments);
    }
}
