<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class License
 *
 * @since 2.0
 */
class License extends Node
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $url = '';


    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}