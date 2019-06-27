<?php declare(strict_types=1);


namespace Swoft\Breaker\State;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class HalfOpenState
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class HalfOpenState extends AbstractState
{
    public function reset(): void
    {
        $this->breaker->resetSucCount();
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function exception(): void
    {
        parent::exception();
        $this->breaker->moveToOpen();
    }
}