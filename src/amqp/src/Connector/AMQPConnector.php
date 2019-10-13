<?php declare(strict_types=1);

namespace Swoft\Amqp\Connector;

use PhpAmqpLib\Channel\AMQPChannel;
use Swoft\Amqp\Contract\ConnectorInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class AMQPConnector
 *
 * @since 2.0
 *
 * @Bean()
 */
class AMQPConnector implements ConnectorInterface
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $queue;

    /**
     * @param array $config
     * @param array $option
     *
     * @return Object|void
     */
    public function connect(array $config, array $option)
    {

    }

    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }
}