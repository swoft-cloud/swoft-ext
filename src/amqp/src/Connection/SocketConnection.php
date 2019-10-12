<?php declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class SocketConnection
 *
 * @since   2.0
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @package Swoft\Amqp\Connection
 */
class SocketConnection extends Connection
{
    public function create(): void
    {
    }
}