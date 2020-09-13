<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Consul\Helper;

/**
 * Class OptionsResolver
 *
 * @since 2.0
 */
class OptionsResolver
{
    /**
     * @param array $options
     * @param array $availableOptions
     *
     * @return array
     */
    public static function resolve(array $options, array $availableOptions): array
    {
        return array_intersect_key($options, array_flip($availableOptions));
    }
}
