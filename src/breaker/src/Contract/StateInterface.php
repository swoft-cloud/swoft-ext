<?php declare(strict_types=1);


namespace Swoft\Breaker\Contract;

/**
 * Class StateInterface
 *
 * @since 2.0
 */
interface StateInterface
{
    /**
     * Reset
     */
    public function reset(): void;

    /**
     * Success
     */
    public function success(): void;

    /**
     * Exception
     */
    public function exception(): void;
}