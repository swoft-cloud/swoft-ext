<?php declare(strict_types=1);


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
    public function setSchema(array $schema): void
    {
        $this->schema = $schema;
    }
}