<?php declare(strict_types=1);


namespace SwoftTest\Limiter\Unit;


use Swoft\Bean\BeanFactory;
use SwoftTest\Db\Unit\TestCase;
use SwoftTest\Limiter\Testing\RateLimiterBean;

class RateLimiterTest extends TestCase
{
    public function testIndex()
    {
        /* @var RateLimiterBean $rateLimiterBean */
        $rateLimiterBean = BeanFactory::getBean(RateLimiterBean::class);
        var_dump($rateLimiterBean->method());
        var_dump($rateLimiterBean->method());
        var_dump($rateLimiterBean->method());
        var_dump($rateLimiterBean->method());
        var_dump($rateLimiterBean->method());
    }
}