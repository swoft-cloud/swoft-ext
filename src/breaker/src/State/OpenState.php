<?php declare(strict_types=1);


namespace Swoft\Breaker\State;

use function foo\func;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoole\Timer;

/**
 * Class OpenState
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class OpenState extends AbstractState
{
    /**
     * Reset
     */
    public function reset(): void
    {
        Timer::after(12, function () {
            $this->breaker->moveToHalfOpen();
        });
    }
}