<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Swagger\Annotation\Mapping\ApiContact;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiContractParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiContact::class)
 */
class ApiContractParser extends Parser
{
    /**
     * @param int        $type
     * @param ApiContact $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_CLASS) {
            throw new SwaggerException(
                sprintf('`@ApiContact` must be on class class=%s', $this->className)
            );
        }

        ApiRegister::registerContract($annotationObject);
        return [];
    }
}