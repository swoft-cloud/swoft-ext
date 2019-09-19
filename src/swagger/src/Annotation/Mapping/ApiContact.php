<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiContact
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiContact
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $email = '';
}