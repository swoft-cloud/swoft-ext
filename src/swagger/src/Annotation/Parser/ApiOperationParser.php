<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\ApiRegister;

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
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type == self::TYPE_METHOD) {
            ApiRegister::registerPaths($this->className, $this->methodName, $annotationObject);
        }

        return [];
    }
}