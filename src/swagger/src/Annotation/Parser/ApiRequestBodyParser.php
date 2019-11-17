<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiRequestBodyParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiRequestBody::class)
 */
class ApiRequestBodyParser extends Parser
{
    /**
     * @param int            $type
     * @param ApiRequestBody $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_METHOD) {
            throw new SwaggerException(
                sprintf('`@ApiRequestBody` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerPaths($this->className, $this->methodName, $annotationObject);
        return [];
    }
}