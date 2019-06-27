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
     * @return bool
     * @throws Exception
     */
    public function method(string $name, int $count): bool
    {
        throw new Exception('Breaker test');
    }

    /**
     * @param string $name
     * @param int    $count
     *
     * @return bool
     */
    public function fallMethod(string $name, int $count): bool
    {
        var_dump($name, $count);

        return false;
    }
}