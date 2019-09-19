<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiResponse
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiResponse
{
    /**
     * @var string
     */
    private $status = '200';

    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @var string
     */
    private $charset = 'UTF-8';

    /**
     * @var string
     */
    private $schema = '';
}