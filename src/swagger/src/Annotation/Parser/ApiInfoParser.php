<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
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
            CLog::debug('`@ApiInfo` must be on Class');
            return [];
        }

        ApiRegister::registerInfo($annotationObject);
        return [];
    }
}