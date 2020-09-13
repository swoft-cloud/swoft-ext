<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Crontab\Listener;

use Swoft\Context\Context;
use Swoft\Crontab\CrontabContext;
use Swoft\Crontab\CrontabEvent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;

/**
 * Class BeforeCrontabListener
 *
 * @since 2.0
 *
 * @Listener(event=CrontabEvent::BEFORE_CRONTAB)
 */
class BeforeCrontabListener implements EventHandlerInterface
{
    /**
     * Event name
     */
    public const EVENT_NAME = 'crontab';

    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        [$beanName, $methodName] = $event->getParams();
        $context = CrontabContext::new($beanName, $methodName);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => self::EVENT_NAME,
                'uri'         => (string)1,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
