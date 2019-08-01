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
    private static $crontabObj = [];

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
     * @param Cron $objAnnotation
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
        self::$crontabObj[] = ['class' => $className, 'method' => $methodName, 'cron' => $cronExpression];
    }

    /**
     * @param int|null $timeStamp
     *
     * @return array
     */
    public static function getCronTasks(int $timeStamp = null): array
    {
        $start_time = empty($timeStamp) ? time() : $timeStamp;
        $date[] = (int)date('s', $start_time);
        $date[] = (int)date('i', $start_time);
        $date[] = (int)date('H', $start_time);
        $date[] = (int)date('d', $start_time);
        $date[] = (int)date('m', $start_time);
        $date[] = (int)date('w', $start_time);
        $task_arr = array();
        foreach (self::$crontabObj as $item) {
            list('class' => $className, 'method' => $methodName, 'cron' => $cron) = $item;
            array_push($task_arr, [$className, $methodName, self::$scheduledClasses[$className]]);
            $cron_arr_date = CrontabExpression::parseCronItem($cron);
            foreach ($cron_arr_date as $k => $cron_item) {
                if ($cron_item === '*' || $cron_item === '?') {
                    continue;
                }
                if (!in_array($date[$k], $cron_item)) {
                    array_pop($task_arr);
                    break;
                }
            }
        }

        return $task_arr;
    }
}
