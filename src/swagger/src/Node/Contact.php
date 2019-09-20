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
     * Contact constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $email
     */
    public function __construct(string $name, string $url, string $email)
    {
        $this->name  = $name;
        $this->url   = $url;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getEmail(): string
    {
        return $this->email;
    }
}