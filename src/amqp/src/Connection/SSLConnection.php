<?php

declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class SSLConnection.
 *
 * @since   2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class SSLConnection extends Connection
{
    /**
     * connect.
     *
     * @param $auths
     * @param $options
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function connect($auths, $options)
    {
        return AMQPSSLConnection::create_connection($auths, $options);
    }
}
