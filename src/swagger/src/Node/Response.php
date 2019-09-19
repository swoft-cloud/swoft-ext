<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Response
 *
 * @since 2.0
 */
class Response
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Header
     */
    protected $headers = [];

    /**
     * @var MediaType[]
     */
    protected $content = [];
}