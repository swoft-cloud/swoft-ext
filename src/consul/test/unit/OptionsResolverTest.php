<?php declare(strict_types=1);


namespace SwoftTest\Consul\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Consul\Helper\OptionsResolver;

/**
 * Class OptionsResolverTest
 *
 * @since 2.0
 */
class OptionsResolverTest extends TestCase
{
    public function testResolve()
    {
        $options = [
            'foo'   => 'bar',
            'hello' => 'world',
            'baz'   => 'inga',
        ];

        $availableOptions = [
            'foo',
            'baz',
        ];

        $result = OptionsResolver::resolve($options, $availableOptions);

        $expected = [
            'foo' => 'bar',
            'baz' => 'inga',
        ];

        $this->assertSame($expected, $result);
    }

    public function testResolveWithoutMatchingOptions()
    {
        $options = [
            'hello' => 'world',
        ];

        $availableOptions = [
            'foo',
            'baz',
        ];

        $result = OptionsResolver::resolve($options, $availableOptions);
        $this->assertSame([], $result);
    }
}
