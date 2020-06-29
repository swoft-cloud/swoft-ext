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
