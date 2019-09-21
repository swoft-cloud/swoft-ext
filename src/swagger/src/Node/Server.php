<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Server extends Node
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var ServerVariable[]
     */
    protected $variables = [];

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param ServerVariable[] $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }
}