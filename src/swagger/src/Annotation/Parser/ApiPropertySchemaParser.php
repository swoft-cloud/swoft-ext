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

use PhpDocReader\AnnotationException;
use ReflectionException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Swagger\Annotation\Mapping\ApiPropertySchema;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiPropertySchemaParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiPropertySchema::class)
 */
class ApiPropertySchemaParser extends Parser
{
    /**
     * @param int               $type
     * @param ApiPropertySchema $annotationObject
     *
     * @return array
     * @throws SwaggerException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_PROPERTY) {
            throw new SwaggerException(
                sprintf('`@ApiPropertySchema` must be on property class=%s', $this->className)
            );
        }

        ApiRegister::registerPropertySchema($this->className, $this->propertyName, $annotationObject);
        return [];
    }
}
