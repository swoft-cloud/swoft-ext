<?php declare(strict_types=1);


namespace SwoftTest\Breaker\Unit;


use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Breaker\Breaker;
use Swoft\Breaker\BreakerManager;
use Swoft\Co;
use SwoftTest\Breaker\Testing\BreakerBean;
use Swoole\Coroutine;

/**
 * Class BreakerTest
 *
 * @since 2.0
 */
class BreakerTest extends TestCase
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function testFallback()
    {
        $breaker = $this->getBreaker(BreakerBean::class, 'method');

        $fallback = $this->getBreakerBean()->method('swoft', 100);
        $this->assertTrue($breaker->isClose());

        $this->getBreakerBean()->method('swoft', 100);
        $this->getBreakerBean()->method('swoft', 100);

        $this->assertTrue($breaker->isOpen());

        $result = $this->getBreakerBean()->method('swoft', 1);
        $this->assertEquals('fallback-swoft-1', $result);
        $this->assertTrue($breaker->isOpen());

        // Sleep
        Coroutine::sleep($breaker->getRetryTime());

        $this->assertTrue($breaker->isHalfOpen());

        $result = $this->getBreakerBean()->method('swoft', 1);
        $this->assertEquals('method-1', $result);

        $this->assertTrue($breaker->isHalfOpen());

        $result = $this->getBreakerBean()->method('swoft', 2);
        $this->assertEquals('method-2', $result);
        $this->assertTrue($breaker->isHalfOpen());

        $result = $this->getBreakerBean()->method('swoft', 3);
        $this->assertEquals('method-3', $result);
        $this->assertTrue($breaker->isClose());


        $this->assertEquals($fallback, 'fallback-swoft-100');
    }

    /**
     * @return BreakerBean
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function getBreakerBean(): BreakerBean
    {
        return bean(BreakerBean::class);
    }

    private function getBreaker(string $className, string $method): Breaker
    {
        return $this->getBreakerManager()->getBreaker($className, $method);
    }

    /**
     * @return BreakerManager
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function getBreakerManager(): BreakerManager
    {
        return bean(BreakerManager::class);
    }
}