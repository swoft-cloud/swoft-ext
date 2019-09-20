<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

use JsonSerializable;

/**
 * Class Node
 *
 * @since 2.0
 */
abstract class Node implements JsonSerializable
{
    /**
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}