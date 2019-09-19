<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class RequestBody
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var MediaType[]
     */
    protected $content;

    /**
     * @var bool
     */
    protected $required = false;
}