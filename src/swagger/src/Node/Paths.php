<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

use JsonSerializable;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Paths
 *
 * @since 2.0
 */
class Paths implements JsonSerializable
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * Paths constructor.
     *
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [];
    }
}