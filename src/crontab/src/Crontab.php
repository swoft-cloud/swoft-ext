<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Crontab;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Crontab\Exception\CrontabException;
use Swoft\Timer;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use function method_exists;
use function sprintf;
use function time;

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
     */
    public function tick(): void
    {
        Timer::tick($this->tickTime * 1000, function (): void {
            // All task
            $tasks = CrontabRegister::getCronTasks(time());

            // Push task to channel
            foreach ($tasks as $task) {
                $this->channel->push($task);
            }
        });
    }

    /**
     * Exec task
     */
    public function dispatch(): void
    {
        while (true) {
            $task = $this->channel->pop();

            Coroutine::create(function () use ($task): void {
                [$beanName, $methodName] = $task;

                // Before
                Swoft::trigger(CrontabEvent::BEFORE_CRONTAB, $this, $beanName, $methodName);

                // Execute task
                $this->execute($beanName, $methodName);

                // After
                Swoft::trigger(CrontabEvent::AFTER_CRONTAB, $this, $beanName, $methodName);
            });
        }
    }

    /**
     * @param string $beanName
     * @param string $method
     *
     * @throws CrontabException
     */
    public function execute(string $beanName, string $method): void
    {
        $object = BeanFactory::getBean($beanName);

        if (!method_exists($object, $method)) {
            throw new CrontabException(sprintf('Crontab(name=%s method=%s) method is not exist!', $beanName, $method));
        }

        $object->$method();
    }
}
