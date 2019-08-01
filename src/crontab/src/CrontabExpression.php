<?php declare(strict_types=1);

namespace Swoft\Crontab;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class CrontabFormat
 *
 * @since 2.0
 *
 * @Bean()
 */
class CrontabExpression
{
    /**
     * @param string $crontab
     *
     * @return bool
     */
    public static function parse(string $crontab): bool
    {
        $cronParts = preg_split('/\s/', $crontab, -1, PREG_SPLIT_NO_EMPTY);
        if (count($cronParts) < 1 || count($cronParts) > 6) {
            return false;
        }
        foreach ($cronParts as $key => $cronPart) {
            $pattern = '/^\d$|^\*$|^\?$|^\d+\-\d+$|^[\d\*]+\/\d+$|^\d[\,\d\,]*\d$/i';
            if (!preg_match($pattern, $cronPart) || !self::checkItem($key, $cronPart)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $key
     * @param string $value
     *
     * @return bool
     */
    protected static function checkItem(int $key, string $value): bool
    {
        switch ($key) {
            case 0://sec
            case 1://min
                if (!self::checkCronItem($value, 0, 59)) {
                    return false;
                }
                break;
            case 2://hour
                if (!self::checkCronItem($value, 0, 23)) {
                    return false;
                }
                break;
            case 3://day
                if (!self::checkCronItem($value, 1, 31)) {
                    return false;
                }
                break;
            case 4://month
                if (!self::checkCronItem($value, 1, 12)) {
                    return false;
                }
                break;
            case 5://week
                if (!self::checkCronItem($value, 0, 6)) {
                    return false;
                }
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * @param string $value
     *
     * @param int $rangeStart
     * @param int $rangeEnd
     *
     * @return bool
     */
    protected static function checkCronItem(string $value, int $rangeStart, int $rangeEnd): bool
    {
        if ('*' === $value || '?' === $value) {
            return true;
        }
        if (strpos($value, '/') !== false) {
            str_replace('*', '0', '$value');
            list($start, $end) = explode('/', $value);
            if (!ctype_digit($start) || !ctype_digit($end)) {
                return false;
            }
            if ($start < $rangeStart || $end > $rangeEnd) {
                return false;
            }
        }
        if (strpos($value, '-') !== false) {
            list($start, $end) = explode('-', $value);
            if (!ctype_digit($start) || !ctype_digit($end)) {
                return false;
            }
            if ($start < $rangeStart || $end > $rangeEnd || $end < $start) {
                return false;
            }
        }
        if (strpos($value, ',') !== false) {
            $items = explode(',', $value);
            foreach ($items as $item) {
                if (!ctype_digit($item)) {
                    return false;
                }
                if ($item < $rangeStart || $item > $rangeEnd) {
                    return false;
                }
            }
        }
        if (!ctype_digit($value) && $value < $rangeStart || $value > $rangeEnd) {
            return false;
        }
        return true;
    }

    /**
     * @param string $cron_str
     *
     * @return array
     */
    public static function parseCronItem(string $cron_str): array
    {
        $cron_arr = preg_split('/\s/', $cron_str, -1, PREG_SPLIT_NO_EMPTY);
        $return_arr = array();
        foreach ($cron_arr as $k => $item) {
            if ('*' === $item || '?' === $item) {
                $return_arr [$k] = $item;
            }
            if (strpos($item, '/') !== false) {
                str_replace('*', '0', '$value');
                list($start, $end) = explode('/', $item);
                while ($start <= 59) {
                    $return_arr [$k][] = $start;
                    $start += $end;
                }
            }
            if (strpos($item, '-') !== false) {
                list($start, $end) = explode('-', $item);
                $return_arr[$k] = range($start, $end);
            }
            if (strpos($item, ',') !== false) {
                $return_arr[$k] = explode(',', $item);
            }
            if (ctype_digit($item)) {
                $return_arr[$k][] = $item;
            }
        }
        return $return_arr;
    }
}
