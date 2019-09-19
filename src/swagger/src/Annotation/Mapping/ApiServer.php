<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiServer
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class ApiServer
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $description = '';
}