<?php declare(strict_types=1);

namespace Swoft\Http\Session\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\ServerEvent;

/**
 * Class PipeMessageListener
 *
 * @Listener(ServerEvent::PIPE_MESSAGE)
 */
class PipeMessageListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}
