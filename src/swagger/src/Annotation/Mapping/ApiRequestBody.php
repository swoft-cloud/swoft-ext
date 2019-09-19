<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiRequestBody
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiRequestBody
{
    /**
     * @var string
     */
    private $description = 'Request body';

    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @var bool
     */
    private $required = false;

    /**
     * @var string
     */
    private $schema = '';
}