<?php declare(strict_types=1);


namespace SwoftTest\Swoole\Tracker\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Swoole\Tracker\Middleware\SwooleTrackerMiddleware;
use Swoft\Swoole\Tracker\SwooleTracker;

/**
 * Class DashboardTest
 *
 * @since 2.0
 */
class DashboardTest extends TestCase
{
    public function testStatus()
    {
        /** @var SwooleTracker $swooleTracker */
        $swooleTracker = bean(SwooleTracker::class);
        var_dump($swooleTracker,bean(SwooleTrackerMiddleware::class));
    }
}
