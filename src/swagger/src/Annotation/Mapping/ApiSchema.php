<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiSchema
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class ApiSchema
{
    /**
     * @var string
     */
    private $name = '';
}