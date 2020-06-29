<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiResponseParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiResponse::class)
 */
class ApiResponseParser extends Parser
{
    /**
     * @param int         $type
     * @param ApiResponse $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_METHOD) {
            throw new SwaggerException(
                sprintf('`@ApiResponse` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerPaths($this->className, $this->methodName, $annotationObject);
        return [];
    }
}
