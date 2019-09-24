<?php

declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use Exception;
use PhpAmqpLib\Connection\AMQPSocketConnection;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class SocketConnection.
 *
 * @since   2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class SocketConnection extends Connection
{
    /**
     * connect.
     *
     * @param $auths
     * @param $options
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function connect($auths, $options)
    {
        return AMQPSocketConnection::create_connection($auths, $options);
    }
}
