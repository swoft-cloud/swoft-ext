<?php declare(strict_types=1);


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
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}