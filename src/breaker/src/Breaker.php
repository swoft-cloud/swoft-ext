<?php declare(strict_types=1);


namespace Swoft\Breaker;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Breaker\Contract\StateInterface;
use Swoft\Breaker\Exception\BreakerException;
use Swoft\Breaker\State\CloseState;
use Swoft\Breaker\State\HalfOpenState;
use Swoft\Breaker\State\OpenState;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Coroutine\Channel;
use Throwable;

/**
 * Class Breaker
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Breaker
{
    use PrototypeTrait;

    /**
     * @var StateInterface
     */
    private $state;

    /**
     * @var int
     */
    private $failCount = 0;

    /**
     * @var int
     */
    private $sucCount = 0;

    /**
     * @var float
     */
    private $timeout = 0;

    /**
     * @var callable|array
     */
    private $fallback;

    /**
     * @param array $config
     *
     * @return Breaker
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(array $config): self
    {
        $self = new self();

        $self->moveToClose();
        return $self;
    }

    /**
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->state instanceof CloseState;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->state instanceof OpenState;
    }

    /**
     * @return bool
     */
    public function isHalfOpen(): bool
    {
        return $this->state instanceof HalfOpenState;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function moveToOpen(): void
    {
        $this->state = OpenState::new($this);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function moveToClose(): void
    {
        $this->state = OpenState::new($this);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function moveToHalfOpen(): void
    {
        $this->state = OpenState::new($this);
    }

    /**
     * @return int
     */
    public function incSucCount(): int
    {
        $this->sucCount++;
        return $this->sucCount;
    }

    /**
     * Reset sucCount
     */
    public function resetSucCount(): void
    {
        $this->sucCount = 0;
    }

    /**
     * @return bool
     */
    public function isReachSucCount(): bool
    {
        return true;
    }

    /**
     * @return int
     */
    public function incFailCount(): int
    {
        $this->failCount++;
        return $this->failCount;
    }

    /**
     * Reset failCount
     */
    public function resetFailCount(): void
    {
        $this->failCount = 0;
    }

    /**
     * @return bool
     */
    public function isReachFailThreshold(): bool
    {
        return true;
    }

    /**
     * @param callable|array $callback
     * @param array          $params
     *
     * @return mixed
     * @throws Throwable
     */
    public function run($callback, $params = [])
    {
        try {
            if ($this->timeout === 0) {
                $result = PhpHelper::call($callback, ...$params);
                $this->state->success();

                return $result;
            }

            $channel = new Channel(1);
            sgo(function () use ($callback, $params, $channel) {
                $result = PhpHelper::call($callback, ...$params);
                $channel->push([$result]);
            });

            $result = $channel->pop($this->timeout);
            if ($result === false) {
                throw new BreakerException(
                    sprintf(
                        'Breaker(callback=%s params=%s) call timeout(%f)',
                        JsonHelper::encode($callback),
                        JsonHelper::encode($params),
                        (float)$this->timeout
                    )
                );
            }

            $this->state->success();
            return $result;
        } catch (Throwable $e) {
            $this->state->exception();

            if (!empty($this->fallback)) {
                return PhpHelper::call($this->fallback, ...$params);
            }

            throw $e;
        }
    }
}