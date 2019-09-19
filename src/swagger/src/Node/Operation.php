<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Operation
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