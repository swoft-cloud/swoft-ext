<?php declare(strict_types=1);


namespace SwoftTest\Breaker\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Breaker\Breaker;
use SwoftTest\Breaker\Testing\BreakerBean;

/**
 * Class BreakerTest
 *
 * @since 2.0
 */
class BreakerTest extends TestCase
{
    public function testIndex()
    {
        /* @var BreakerBean $breakerBean*/
        $breakerBean = bean(BreakerBean::class);
        $breakerBean->method('swoft', 100);
        $this->assertTrue(true);
    }
}