<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard;


use StatsCenter;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use Throwable;

/**
 * Class Swoole Dashboard function switch control
 *
 * @since 2.0
 *
 * @Bean("swooleDashboard")
 */
class SwooleDashboard
{
    /**
     * Check for memory leaks
     *
     * @var bool
     */
    private $memoryLeakCheck = false;

    /**
     * Check Blocking detection
     *
     * @var bool
     */
    private $blockCheck = false;

    /**
     * Performance analysis
     *
     * Be careful not to open multiple tools at the same time.
     *
     * For example,
     *    both "startXhprof()" and "startBlockCheck()" in the code will affect each other and cause inaccurate results.
     *
     * @var bool
     */
    private $performanceAnalysis = false;

    /**
     * Distributed link tracking
     *
     * @var bool
     */
    private $linkTracking = true;

    /**
     * @return bool
     */
    public function isMemoryLeakCheck(): bool
    {
        return $this->memoryLeakCheck;
    }

    /**
     * @return bool
     */
    public function isBlockCheck(): bool
    {
        return $this->blockCheck;
    }

    /**
     * @return bool
     */
    public function isPerformanceAnalysis(): bool
    {
        return $this->performanceAnalysis;
    }

    /**
     * @return bool
     */
    public function isLinkTracking(): bool
    {
        return $this->linkTracking;
    }

    /**
     * start analysis
     *
     * @return void
     */
    public function startAnalysis(): void
    {
        if ($this->isBlockCheck()) {
            $this->startBlockCheck();
        }
        if ($this->isMemoryLeakCheck()) {
            $this->startMemoryLeakCheck();
        }
        if ($this->isPerformanceAnalysis()) {
            $this->startPerformanceAnalysis();
        }
    }

    /**
     * end analysis
     *
     * @return void
     */
    public function endAnalysis(): void
    {
        if ($this->isBlockCheck()) {
            $this->endBlockCheck();
        }
        if ($this->isMemoryLeakCheck()) {
            $this->endMemoryLeakCheck();
        }
        if ($this->isPerformanceAnalysis()) {
            $this->endPerformanceAnalysis();
        }
    }

    /**
     * Start memory leak check buried point
     *
     * @return void
     */
    public function startMemoryLeakCheck(): void
    {
        if (function_exists('startMemleakCheck')) {
            startMemleakCheck();
        } else {
            CLog::error('startMemleakCheck function not found, Please check swoole_plus extend');
        }
    }

    /**
     * End memory leak check buried point
     *
     * @return void
     */
    public function endMemoryLeakCheck(): void
    {
        if (function_exists('endMemleakCheck')) {
            endMemleakCheck();
        }
    }

    /**
     * Start block check buried point
     *
     * @return void
     */
    public function startBlockCheck(): void
    {
        if (function_exists('startBlockCheck')) {
            startBlockCheck();
        } else {
            CLog::error('startBlockCheck function not found, Please check swoole_plus extend');
        }
    }

    /**
     * End block check buried point
     *
     * @return void
     */
    public function endBlockCheck(): void
    {
        if (function_exists('endBlockCheck')) {
            endBlockCheck();
        }
    }

    /**
     * Start performance analysis buried point
     *
     * @return void
     */
    public function startPerformanceAnalysis(): void
    {
        if (function_exists('startXhprof')) {
            startXhprof();
        } else {
            CLog::error('startPerformanceAnalysis function not found, Please check swoole_plus extend');
        }
    }

    /**
     * End performance analysis buried point
     *
     * @return void
     */
    public function endPerformanceAnalysis(): void
    {
        if (function_exists('endXhprof')) {
            endXhprof();
        }
    }

    /**
     * Start this request analysis link tracking
     *
     * @param string $path
     * @param string $serviceName
     * @param string $serverIp
     * @param string $traceId
     * @param string $spanId
     *
     * @return object|null \StatsCenter_Tick
     * @throws Throwable
     */
    public function startRpcAnalysis(
        string $path,
        string $serviceName,
        string $serverIp,
        string $traceId,
        string $spanId
    ): ?object {
        if (class_exists(StatsCenter::class) === false) {
            CLog::error('StatsCenter::class not found, Please check swoole_plus extend');
            return null;
        }

        try {
            $tick = StatsCenter::beforeExecRpc($path, $serviceName, $serverIp, $traceId, $spanId);

            return $tick;
        } catch (Throwable $e) {
            CLog::error(__FUNCTION__ . $e->getMessage());
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
        if (class_exists(StatsCenter::class) === false) {
            return;
        }

        try {
            StatsCenter::afterExecRpc($tick, $isSuccess, $errno);
        } catch (Throwable $e) {
            CLog::error(__FUNCTION__ . $e->getMessage());
        }
    }
}
