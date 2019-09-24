<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiOperationParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiOperation::class)
 */
class ApiOperationParser extends Parser
{
    /**
     * @param int          $type
     * @param ApiOperation $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_METHOD) {
            throw new SwaggerException(
                sprintf('`@ApiOperation` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerPaths($this->className, $this->methodName, $annotationObject);
        return [];
    }
}