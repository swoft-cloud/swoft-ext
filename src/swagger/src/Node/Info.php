<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Info
 *
 * @since 2.0
 */
class Info extends Node
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $termsOfService = '';

    /**
     * @var Contact
     */
    protected $contact;

    /**
     * @var License
     */
    protected $license;

    /**
     * @var string
     */
    protected $version = '';

    /**
     * Info constructor.
     *
     * @param string $title
     * @param string $description
     * @param string $termsOfService
     * @param string $version
     */
    public function __construct(string $title, string $description, string $termsOfService, string $version)
    {
        $this->title          = $title;
        $this->description    = $description;
        $this->termsOfService = $termsOfService;
        $this->version        = $version;
    }

    /**
     * @param Contact $contact
     */
    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @param License $license
     */
    public function setLicense(License $license): void
    {
        $this->license = $license;
    }
}