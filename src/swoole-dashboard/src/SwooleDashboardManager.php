<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard;

use ReflectionException;
use StatsCenter;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Log\Helper\Log;
use Swoft\Swoole\Dashboard\SwooleDashboard;
use function function_exists;

/**
 * Class SwooleDashboardLogic
 *
 * @Bean()
 */
class SwooleDashboardManager
{

    /**
     * @Inject()
     *
     * @var SwooleDashboard
     */
    private $swoleDashboard;

    /**
     * start analysis
     *
     * @return void
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function startAnalysis(): void
    {
        if ($this->swoleDashboard->isBlockCheck()) {
            $this->startBlockCheck();
        }
        if ($this->swoleDashboard->isMemoryLeakCheck()) {
            $this->startMemoryLeakCheck();
        }
        if ($this->swoleDashboard->isPerformanceAnalysis()) {
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
        if ($this->swoleDashboard->isBlockCheck()) {
            $this->endBlockCheck();
        }
        if ($this->swoleDashboard->isMemoryLeakCheck()) {
            $this->endMemoryLeakCheck();
        }
        if ($this->swoleDashboard->isPerformanceAnalysis()) {
            $this->endPerformanceAnalysis();
        }
    }

    /**
     * Start memory leak check buried point
     *
     * @return void
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function startMemoryLeakCheck(): void
    {
        if (function_exists('startMemleakCheck')) {
            startMemleakCheck();
        } else {
            Log::error('startMemleakCheck function not found, Please check swoole_plus extend');
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
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function startBlockCheck(): void
    {
        if (function_exists('startBlockCheck')) {
            startBlockCheck();
        } else {
            Log::error('startBlockCheck function not found, Please check swoole_plus extend');
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
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function startPerformanceAnalysis(): void
    {
        if (function_exists('startXhprof')) {
            startXhprof();
        } else {
            Log::error('startPerformanceAnalysis function not found, Please check swoole_plus extend');
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
     * @return object|null StatsCenter_Tick
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function startRpcAnalysis(
        string $path,
        string $serviceName,
        string $serverIp,
        string $traceId,
        string $spanId
    ): ?object {
        if (class_exists(StatsCenter::class) === false) {
            Log::error('StatsCenter::class not found, Please check swoole_plus extend');
            return null;
        }

        $tick = StatsCenter::beforeExecRpc($path, $serviceName, $serverIp, $traceId, $spanId);

        return $tick;
    }

    /**
     * End this analysis link tracking
     *
     * @param object $tick StatsCenter_Tick
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

        StatsCenter::afterExecRpc($tick, $isSuccess, $errno);
    }
}
