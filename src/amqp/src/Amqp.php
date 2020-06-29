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

use Closure;
use Swoft\Amqp\Connection\Connection;
use Swoft\Amqp\Connection\ConnectionManager;
use Swoft\Amqp\Exception\AMQPException;
use Swoft\Bean\BeanFactory;
use Throwable;

/**
 * Class Amqp
 *
 * @since   2.0
 *
 * @method static Connection channel(string $channel = null)
 * @method static void push(string $message, array $prop = [], string $route = '')
 * @method static string|null pop()
 * @method static void listen(Closure $callback = null)
 */
class Amqp
{
    /**
     * connection
     *
     * @param string $pool
     *
     * @return Connection
     * @throws AMQPException
     */
    public static function connection(string $pool = Pool::DEFAULT_POOL): Connection
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            /* @var Pool $amqpPool */
            $amqpPool   = BeanFactory::getBean($pool);
            $connection = $amqpPool->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new AMQPException(sprintf(
                'Pool error is %s file=%s line=%d',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new AMQPException(sprintf('%s is not instanceof %s', get_class($connection), Connection::class));
        }

        return $connection;
    }

    /**
     * __callStatic
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws AMQPException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $connection = self::connection();

        return $connection->{$method}(...$arguments);
    }
}
