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

class MediaType extends Node
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * @param array $schema
     */
    public function setSchema($schema): void
    {
        $this->schema = $schema;
    }
}
