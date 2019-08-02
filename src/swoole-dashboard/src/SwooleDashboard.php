<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard;


use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Swoole Dashboard function switch control
 *
 * @Bean()
 */
class SwooleDashboard
{
    /**
     * Check for memory leaks
     *
     * @var bool
     */
    private $memoryLeakCheck;

    /**
     * Check Blocking detection
     *
     * @var bool
     */
    private $blockCheck;

    /**
     * Performance analysis
     *
     * @var bool
     */
    private $performanceAnalysis;

    /**
     * Distributed link tracking
     *
     * @var bool
     */
    private $linkTracking;

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
}
