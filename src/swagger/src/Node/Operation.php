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
     * @var RequestBody
     */
    protected $requestBody;

    /**
     * @var Response[]
     */
    protected $responses;

    /**
     * @var bool
     */
    protected $deprecated = false;

    /**
     * @var Server[]
     */
    protected $servers = [];

    /**
     * @param Server[] $servers
     */
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $operationId
     */
    public function setOperationId(string $operationId): void
    {
        $this->operationId = $operationId;
    }

    /**
     * @param Parameter[] $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $requestBody
     */
    public function setRequestBody($requestBody): void
    {
        $this->requestBody = $requestBody;
    }

    /**
     * @param Response[] $responses
     */
    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    /**
     * @param bool $deprecated
     */
    public function setDeprecated(bool $deprecated): void
    {
        $this->deprecated = $deprecated;
    }
}
