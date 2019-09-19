<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiInfo
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiInfo
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $termsOfService = '';

    /**
     * @var string
     */
    private $version = '';
}