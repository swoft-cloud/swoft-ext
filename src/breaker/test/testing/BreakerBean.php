<?php declare(strict_types=1);


namespace SwoftTest\Breaker\Testing;

use Exception;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Breaker\Annotation\Mapping\Breaker;

/**
 * Class BreakerBean
 *
 * @since 2.0
 *
 * @Bean()
 */
class BreakerBean
{
    /**
     * @Breaker(fallback="fallMethod")
     *
     * @param string $name
     * @param int    $count
     *
     * @return string
     * @throws Exception
     */
    public function method(string $name, int $count): string
    {
        if ($count < 100) {
            return sprintf('method-%d', $count);
        }

        throw new Exception('Breaker test');
    }

    /**
     * @param string $name
     * @param int    $count
     *
     * @return string
     */
    public function fallMethod(string $name, int $count): string
    {
        return sprintf('fallback-%s-%d', $name, $count);
    }
}