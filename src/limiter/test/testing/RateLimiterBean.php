<?php declare(strict_types=1);


namespace SwoftTest\Limiter\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Limiter\Annotation\Mapping\RateLimiter;

/**
 * Class RateLimiterBean
 *
 * @since 2.0
 *
 * @Bean()
 */
class RateLimiterBean
{
    /**
     * @RateLimiter(rate=10)
     *
     * @return string
     */
    public function method(): string
    {
        return 'method';
    }
}