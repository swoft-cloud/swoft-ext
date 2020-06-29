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
use Swoft\Swagger\Annotation\Mapping\ApiEntity;

/**
 * Class ApiEntityParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=ApiEntity::class)
 */
class ApiEntityParser extends Parser
{
    /**
     * @param int       $type
     * @param ApiEntity $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}
