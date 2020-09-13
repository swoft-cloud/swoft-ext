<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger\Node;

use JsonSerializable;
use Swoft\Stdlib\Helper\ObjectHelper;

/**
 * Class Node
 *
 * @since 2.0
 */
abstract class Node implements JsonSerializable
{
    /**
     * Ref key
     */
    public const REF_KEY = 'ref';

    /**
     * Node constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        ObjectHelper::init($this, $data);
    }

    /**
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $value) {
            // Ref key
            if ($key == self::REF_KEY) {
                $key = sprintf('$%s', $key);
            }

            if ($value === null) {
                continue;
            }

            if (is_array($value) && empty($value)) {
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }
}
