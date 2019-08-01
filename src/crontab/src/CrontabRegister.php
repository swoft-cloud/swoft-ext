<?php declare(strict_types=1);

namespace Swoft\Crontab;

use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Exception\CrontabException;

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

        $cronExpression = $objAnnotation->getCron();
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
        $startTime = time();

        $date[] = (int)date('s', $startTime);
        $date[] = (int)date('i', $startTime);
        $date[] = (int)date('H', $startTime);
        $date[] = (int)date('d', $startTime);
        $date[] = (int)date('m', $startTime);
        $date[] = (int)date('w', $startTime);

        $taskArr = array();
        foreach (self::$crontabs as $crontab) {

            ['class' => $className, 'method' => $methodName, 'cron' => $cron] = $crontab;
            array_push($taskArr, [$className, $methodName, self::$scheduledClasses[$className]]);

            $cron_arr_date = CrontabExpression::parseCronItem($cron);
            foreach ($cron_arr_date as $k => $cron_item) {
                if ($cron_item === '*' || $cron_item === '?') {
                    continue;
                }
                if (!in_array($date[$k], $cron_item)) {
                    array_pop($taskArr);
                    break;
                }
            }
        }

        return $taskArr;
    }
}
