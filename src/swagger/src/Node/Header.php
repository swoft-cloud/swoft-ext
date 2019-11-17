<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Header
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Schema
     */
    protected $schema;
}