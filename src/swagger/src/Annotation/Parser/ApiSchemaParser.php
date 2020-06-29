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
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiSchemaParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiSchema::class)
 */
class ApiSchemaParser extends Parser
{
    /**
     * @param int       $type
     * @param ApiSchema $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_CLASS) {
            throw new SwaggerException(
                sprintf('`@ApiSchema` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerSchema($this->className, $annotationObject);

        return [$this->className, $this->className, Bean::PROTOTYPE, ''];
    }
}
