<?php declare(strict_types=1);


namespace Swoft\Swoole\Tracker;


use SwooleTracker\Stats;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use Throwable;

/**
 * Class Swoole Tracker function switch control
 *
 * @since 2.0
 *
 * @Bean()
 */
class SwooleTracker
{
    /**
     * Start this request analysis link tracking
     *
     * @param string $path
     * @param string $serviceName
     * @param string $serverIp
     *
     * @return object|null \StatsCenter_Tick
     * @throws Throwable
     */
    public function startRpcAnalysis(
        string $path,
        string $serviceName,
        string $serverIp,
    ): ?object {
        if (class_exists(Stats::class) === false) {
            CLog::error('Stats::class not found, Please check swoole_tracker extend');
            return null;
        }

        try {
            $tick = Stats::beforeExecRpc($path, $serviceName, $serverIp);

            return $tick;
        } catch (Throwable $e) {
            CLog::error(__FUNCTION__ . ' ' . $e->getMessage());
        }

        return null;
    }

    /**
     * End this analysis link tracking
     *
     * @param object $tick StatsCenter_Tick \StatsCenter_Tick
     * @param bool   $isSuccess
     * @param int    $errno
     *
     * @return void
     */
    public function endRpcAnalysis($tick, bool $isSuccess, int $errno): void
    {
        if (empty($tick)) {
            return;
        }
        if (class_exists(Stats::class) === false) {
            return;
        }

        try {
            Stats::afterExecRpc($tick, $isSuccess, $errno);
        } catch (Throwable $e) {
            CLog::error(__FUNCTION__ . ' ' . $e->getMessage());
        }
    }


}
