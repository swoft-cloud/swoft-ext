<?php declare(strict_types=1);


namespace Swoft\Swagger;


use InvalidArgumentException;
use Swoft\Bean\Concern\PrototypeTrait;

/**
 * Class Schema
 *
 * @since 2.0
 */
abstract class Schema
{
    use PrototypeTrait;

    /***
     * @param array $properties
     *
     * @return Schema
     */
    public static function new(array $properties = []): self
    {
        $self = self::__instance();
        foreach ($properties as $property => $value) {
            if (!property_exists($self, $property)) {
                throw new InvalidArgumentException('Schema args is not exist!');
            }

            $self->{$property} = $value;
        }

        return $self;
    }
}