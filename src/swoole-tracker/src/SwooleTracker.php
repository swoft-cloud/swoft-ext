<?php declare(strict_types=1);


namespace Swoft\Swoole\Tracker;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use SwooleTracker\Stats;
use SwooleTracker\Tick;
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
     * @param string $traceId
     * @param string $spanId
     *
     * @return Tick|null
     * @throws Throwable
     */
    public function startRpcAnalysis(
        string $path,
        string $serviceName,
        string $serverIp,
        string $traceId = '',
        string $spanId = ''
    ) {
        if (class_exists(Stats::class) === false) {
            CLog::error('Stats::class not found, Please check swoole_tracker extend');
            return null;
        }

        try {
            $tick = Stats::beforeExecRpc($path, $serviceName, $serverIp, $traceId, $spanId);

            return $tick;
        } catch (Throwable $e) {
            CLog::error(__FUNCTION__ . ' ' . $e->getMessage());
        }

        return null;
    }

    /**
     * End this analysis link tracking
     *
     * @param Tick $tick Tick
     * @param bool $isSuccess
     * @param int  $errno
     *
     * @return void
     */
    public function endRpcAnalysis($tick, bool $isSuccess, int $errno): void
    {
        if ($tick === null) {
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
