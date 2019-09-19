<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiLicense
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiLicense
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $url = '';
}