<?php declare(strict_types=1);


namespace Swoft\Breaker;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class BreakerManager
 *
 * @since 2.0
 *
 * @Bean()
 */
class BreakerManager
{
    /**
     * @var Breaker[]
     *
     * @example
     * [
     *     'className:method' => new Breaker(),
     *     'className:method' => new Breaker()
     * ]
     */
    private $breakers = [];
}