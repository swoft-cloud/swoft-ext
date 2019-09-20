<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
use Swoft\Swagger\Annotation\Mapping\ApiLicense;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiLicenseParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiLicense::class)
 */
class ApiLicenseParser extends Parser
{
    /**
     * @param int        $type
     * @param ApiLicense $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_CLASS) {
            CLog::debug('`@ApiLicense` must be on Class');
            return [];
        }

        ApiRegister::registerLicense($annotationObject);
        return [];
    }
}