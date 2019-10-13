<?php declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class StreamConnection
 *
 * @since   2.0
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @package Swoft\Amqp\Connection
 */
class StreamConnection extends Connection
{
    /**
     * connect
     * @param $auths
     * @param $options
     *
     * @return mixed
     * @throws \Exception
     */
    public function connect($auths, $options)
    {
        return AMQPStreamConnection::create_connection($auths, $options);
    }
}