<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing;


use Swoft\SwoftComponent;

/**
 * Class Autoloader
 *
 * @since 2.0
 */
class Autoloader extends SwoftComponent
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }
}