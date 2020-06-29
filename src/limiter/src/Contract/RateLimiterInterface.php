<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Limiter\Contract;

/**
 * Class RateLimiterInterface
 *
 * @since 2.0
 */
interface RateLimiterInterface
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function getTicket(array $config): bool;
}
