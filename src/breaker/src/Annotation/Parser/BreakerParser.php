<?php declare(strict_types=1);


namespace Swoft\Breaker\Annotation\Parser;


use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Breaker\Annotation\Mapping\Breaker;

/**
 * Class BreakerParser
 *
 * @since 2.0
 *
 * @AnnotationParser(Breaker::class)
 */
class BreakerParser extends Parser
{
    /**
     * @param int     $type
     * @param Breaker $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        return [];
    }
}