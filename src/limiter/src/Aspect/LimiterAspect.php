<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Limiter\Aspect;

use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Swoft\Aop\Proxy;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Limiter\Annotation\Mapping\RateLimiter;
use Swoft\Limiter\RateLimter;
use Throwable;

/**
 * Class LimiterAspect
 *
 * @since 2.0
 *
 * @Aspect()
 * @PointAnnotation(
 *     include={RateLimiter::class}
 * )
 */
class LimiterAspect
{
    /**
     * @Inject("rateLimiter")
     *
     * @var RateLimter
     */
    private $rateLimiter;

    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     * @throws Throwable
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $args      = $proceedingJoinPoint->getArgs();
        $target    = $proceedingJoinPoint->getTarget();
        $method    = $proceedingJoinPoint->getMethod();
        $className = get_class($target);
        $className = Proxy::getOriginalClassName($className);

        $result = $this->rateLimiter->checkRate([$proceedingJoinPoint, 'proceed'], $className, $method, $target, $args);
        return $result;
    }
}
