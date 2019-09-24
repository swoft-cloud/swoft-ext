<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Swagger\Annotation\Mapping\ApiProperty;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiPropertyParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiProperty::class)
 */
class ApiPropertyParser extends Parser
{
    /**
     * @param int         $type
     * @param ApiProperty $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_PROPERTY) {
            throw new SwaggerException(
                sprintf('`@ApiProperty` must be on property class=%s', $this->className)
            );
        }

        ApiRegister::registerProperty($this->className, $this->propertyName, $annotationObject);
        return [];
    }
}