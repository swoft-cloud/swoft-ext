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
     * License constructor.
     *
     * @param string $name
     * @param string $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url  = $url;
    }
}