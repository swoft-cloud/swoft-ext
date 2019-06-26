<?php declare(strict_types=1);


namespace SwoftTest\Consul\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Consul\Consul;
use SwoftTest\Consul\Testing\TestBean;

/**
 * Class ConsulTest
 *
 * @since 2.0
 */
class ConsulTest extends TestCase
{
    public function testIndex()
    {
        $this->assertTrue(true);
    }
}