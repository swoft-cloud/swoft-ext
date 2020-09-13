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
use Swoft\Swagger\Annotation\Mapping\ApiInfo;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiInfoParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiInfo::class)
 */
class ApiInfoParser extends Parser
{
    /**
     * @param int     $type
     * @param ApiInfo $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_CLASS) {
            throw new SwaggerException(
                sprintf('`@ApiInfo` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerInfo($annotationObject);
        return [];
    }
}
