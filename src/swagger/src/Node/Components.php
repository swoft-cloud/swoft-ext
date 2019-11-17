<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Components
 *
 * @since 2.0
 */
class Components extends Node
{
    protected $schemas = [];

    protected $responses = [];

    protected $examples = [];

    protected $requestBodies = [];

    protected $headers = [];

    /**
     * @param array $schemas
     */
    public function setSchemas(array $schemas): void
    {
        $this->schemas = $schemas;
    }

    /**
     * @param array $responses
     */
    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    /**
     * @param array $examples
     */
    public function setExamples(array $examples): void
    {
        $this->examples = $examples;
    }

    /**
     * @param array $requestBodies
     */
    public function setRequestBodies(array $requestBodies): void
    {
        $this->requestBodies = $requestBodies;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}