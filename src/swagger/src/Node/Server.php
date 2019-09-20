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
     * Server constructor.
     *
     * @param string $url
     * @param string $description
     */
    public function __construct(string $url, string $description)
    {
        $this->url         = $url;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return ServerVariable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}