<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Response
 *
 * @since 2.0
 */
class Response extends Node
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

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param Header $headers
     */
    public function setHeaders(Header $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param MediaType[] $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }
}