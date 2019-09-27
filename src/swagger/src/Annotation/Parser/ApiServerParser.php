<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Log\Helper\CLog;
use Swoft\Swagger\Annotation\Mapping\ApiServer;
use Swoft\Swagger\ApiRegister;
use Swoft\Swagger\Exception\SwaggerException;

/**
 * Class ApiServerParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiServer::class)
 */
class ApiServerParser extends Parser
{
    /**
     * @param int       $type
     * @param ApiServer $annotationObject
     *
     * @return array
     * @throws SwaggerException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type == self::TYPE_CLASS) {
            ApiRegister::registerServers($annotationObject);
            return [];
        } elseif ($type == self::TYPE_METHOD) {
            ApiRegister::registerPaths($this->className, $this->methodName, $annotationObject);
            return [];
        }

        throw new SwaggerException(
            sprintf('`@ApiResponse` must be on class Or method class=%s', $this->className)
        );
    }
}