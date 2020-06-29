<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
