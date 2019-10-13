<?php declare(strict_types=1);

namespace Swoft\Amqp\Connection;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class SSLConnection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class SSLConnection extends Connection
{
    public function create(): void
    {
    }
}