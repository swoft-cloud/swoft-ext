<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Breaker\Contract;

/**
 * Class StateInterface
 *
 * @since 2.0
 */
interface StateInterface
{
    /**
     * Check status
     */
    public function check(): void;

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
