<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiEntity
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class ApiEntity
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $unfields = [];
}