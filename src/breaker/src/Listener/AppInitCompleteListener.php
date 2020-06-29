<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Breaker\Listener;

use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Breaker\BreakerManager;
use Swoft\Breaker\BreakerRegister;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\SwoftEvent;

/**
 * Class AppInitCompleteListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var BreakerManager
     */
    private $breakerManger;

    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        $breakers = BreakerRegister::getBreakers();
        $this->breakerManger->initBreaker($breakers);
    }
}
