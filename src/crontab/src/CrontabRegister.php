<?php declare(strict_types=1);

namespace Swoft\Crontab;

use ReflectionException;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Exception\CrontabException;
use Swoft\Stdlib\Helper\PhpHelper;

class CrontabRegister
{
    /**
     * @var array
     * @example
     * [
     *      [
     *          'class'=>xxx,
     *          'method'=>xxx,
     *          'cron'=>'* * * * * *'
     *      ]
     * ]
     */
    private static $crontabs = [];

    /**
     * @var array
     * @example
     * [
     *      'className' => 'schenduledName'
     * ]
     */
    private static $scheduledClasses = [];

    /**
     * @param string $className
     * @param string $schenduledName
     *
     */
    public static function registerScheduled(string $className, string $schenduledName): void
    {
        self::$scheduledClasses[$className] = $schenduledName;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param Cron   $objAnnotation
     *
     * @throws CrontabException
     */
    public static function registerCron(string $className, string $methodName, $objAnnotation): void
    {
        if (!isset(self::$scheduledClasses[$className])) {
            throw new CrontabException(
                sprintf('%s must be define class `@Scheduled()`', get_class($objAnnotation))
            );
        }

        $cronExpression = $objAnnotation->getValue();
        if (!CrontabExpression::parse($cronExpression)) {
            throw new CrontabException(
                sprintf('`%s::%s()` `@Cron()` expression format is error', $className, $methodName)
            );
        }


        self::$crontabs[] = ['class' => $className, 'method' => $methodName, 'cron' => $cronExpression];
    }

    /**
     * @return array
     */
    public static function getCronTasks(): array
    {
        $time = time();
        $tasks = array();
        foreach (self::$crontabs as $crontab) {
            ['class' => $className, 'method' => $methodName, 'cron' => $cron] = $crontab;
            if (CrontabExpression::parseObj($cron, $time)) {
                array_push($tasks, [self::$scheduledClasses[$className]], $methodName);
            }
        }
        return $tasks;
    }
}
