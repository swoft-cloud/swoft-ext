<?php declare(strict_types=1);

namespace Swoft\Crontab;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoft\Timer;
use Swoole\Coroutine;

/**
 * Class Crontab
 *
 * @since 2.0
 *
 * @Bean()
 */
class Crontab extends UserProcess
{
    /**
     * @param Process $process
     */
    public function run(Process $process): void
    {
        $channel = new Channel(1);
        Timer::tick(1000, function () use ($channel) {
            $time = time();
            $task = CrontabRegister::getCronTasks($time);
            foreach ($task as $item) {
                $channel->push($item);
            }
        });
        while (true) {
            $item = $channel->pop();
            sgo(function () use ($item) {
                $obj = new $item[0]();
                call_user_func(array($obj, $item[1]));
            });
        }
    }
}
