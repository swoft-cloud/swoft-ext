<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Contact
 *
 * @since 2.0
 */
class Contact extends Node
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
     * @var string
     */
    protected $email = '';

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

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}