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
use Swoft\Swagger\Annotation\Mapping\ApiPropertyEntity;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiPropertyEntityParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiPropertyEntity::class)
 */
class ApiPropertyEntityParser extends Parser
{
    /**
     * @param int               $type
     * @param ApiPropertyEntity $annotationObject
     *
     * @return array
     * @throws ReflectionException
     * @throws SwaggerException
     * @throws AnnotationException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_PROPERTY) {
            throw new SwaggerException(
                sprintf('`@ApiPropertyEntity` must be on property class=%s', $this->className)
            );
        }

        ApiRegister::registerPropertyEntity($this->className, $this->propertyName, $annotationObject);
        return [];
    }
}
