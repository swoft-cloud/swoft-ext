<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

use JsonSerializable;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Paths
 *
 * @since 2.0
 */
class Paths extends Node
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }
}