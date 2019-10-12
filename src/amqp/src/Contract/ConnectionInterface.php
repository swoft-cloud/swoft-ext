<?php declare(strict_types=1);

namespace Swoft\Amqp\Contract;

/**
 * Interface ConnectionInterface
 *
 * @since   2.0
 *
 * @package Swoft\Amqp\Contract
 */
interface ConnectionInterface
{
    /**
     * Create client
     */
    public function createClient(): void;
}