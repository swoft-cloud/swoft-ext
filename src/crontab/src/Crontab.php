<?php declare(strict_types=1);

namespace Swoft\Crontab;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Timer;
use Swoole\Coroutine\Channel;

/**
 * Class Crontab
 *
 * @since 2.0
 *
 * @Bean(name="crontab")
 */
class Crontab
{
    /**
     * Seconds
     *
     * @var float
     */
    private $tickTime = 1;

    /**
     * @var int
     */
    private $maxTask = 10;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * Init
     */
    public function init(): void
    {
        $this->channel = new Channel($this->maxTask);
    }

    /**
     * Tick task
     *
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function tick(): void
    {
        Timer::tick($this->tickTime * 1000, function () {
            // All task
            $tasks = CrontabRegister::getCronTasks();

            // Push task to channel
            foreach ($tasks as $task) {
                $this->channel->push($task);
            }
        });
    }

    /**
     * Exe task
     */
    public function exe(): void
    {
        while (true) {
            $item = $this->channel->pop();
            sgo(function () use ($item) {
                [$beanName,$methodName]=$item;
                CrontabRegister::dispatch($beanName,$methodName);
            });
        }
    }
}
