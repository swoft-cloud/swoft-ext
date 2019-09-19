<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiOperation
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiOperation
{
    /**
     * @var string[]
     */
    private $tags = [];

    /**
     * @var string
     */
    private $summary = '';

    /**
     * Default is description of action function
     *
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $operationId = '';
}