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
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $termsOfService
     */
    public function setTermsOfService(string $termsOfService): void
    {
        $this->termsOfService = $termsOfService;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
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