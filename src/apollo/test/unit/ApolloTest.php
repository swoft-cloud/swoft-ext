<?php declare(strict_types=1);


namespace SwoftTest\Apollo\Unit;


use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Apollo\Config;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;

/**
 * Class ApolloTest
 *
 * @since 2.0
 */
class ApolloTest extends TestCase
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function tearDown()
    {
        Context::getWaitGroup()->wait();
    }

    public function testIndex()
    {
        /* @var Config $config*/
        $config = BeanFactory::getBean(Config::class);
        $data = $config->listen(function ($configs){
            var_dump($configs);
        }, ['application']);

        var_dump($data);
    }
}