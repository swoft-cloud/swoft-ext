<?php declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class StreamConnection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class StreamConnection extends Connection
{
    /**
     * @param array $auths
     * @param array $options
     *
     * @return mixed
     * @throws Exception
     */
    public function connect(array $auths, array $options)
    {
        return AMQPStreamConnection::create_connection($auths, $options);
    }
}