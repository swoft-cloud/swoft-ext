<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class Info
 *
 * @since 2.0
 */
class Info
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
}