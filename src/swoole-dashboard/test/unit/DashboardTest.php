<?php declare(strict_types=1);


namespace SwoftTest\Swoole\Dashboard\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Swoole\Dashboard\SwooleDashboard;

/**
 * Class DashboardTest
 *
 * @since 2.0
 */
class DashboardTest extends TestCase
{
    public function testStatus()
    {
        /** @var $dashboard SwooleDashboard */
        $dashboard = bean(SwooleDashboard::class);

        $this->assertTrue($dashboard->isMemoryLeakCheck());
        $this->assertTrue($dashboard->isBlockCheck());
        $this->assertFalse($dashboard->isPerformanceAnalysis());
        $this->assertTrue($dashboard->isLinkTracking());
    }
}
