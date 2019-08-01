<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard\Logic;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\woole\Dashboard\SwooleDashboard;

/**
 * Class SwooleDashboardLogic
 *
 * @Bean()
 */
class SwooleDashboardLogic
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
     */
    public function startAnalysis(): void
    {

    }

    /**
     * end analysis
     *
     * @return void
     */
    public function endAnalysis(): void
    {

    }
}
