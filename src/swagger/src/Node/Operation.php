<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Operation
 *
 * @since 2.0
 */
class Operation extends Node
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var string
     */
    protected $summary = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $operationId = '';

    /**
     * @var Parameter[]
     */
    protected $parameters = [];

    /**
     * @var
     */
    protected $requestBody;

    /**
     * @var Responses
     */
    protected $responses;

    /**
     * @var bool
     */
    protected $deprecated = false;
}