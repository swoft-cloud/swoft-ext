<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session\Handler;

use Swoft\Http\Session\Concern\AbstractHandler;
use Swoft\Redis\Pool;
use function class_exists;

/**
 * Class RedisHandler
 *
 * @since 2.0.7
 */
class RedisHandler extends AbstractHandler
{
    /**
     * @var Pool
     */
    private $redis;

    /**
     * The prefix for session key
     *
     * @var string
     */
    protected $prefix = 'swoft_sess:';

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(Pool::class);
    }

    /**
     * {@inheritDoc}
     */
    public function read(string $sessionId): string
    {
        $sessKey = $this->getSessionKey($sessionId);

        return (string)$this->redis->get($sessKey);
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $sessionId, string $sessionData): bool
    {
        $sessKey = $this->getSessionKey($sessionId);

        return (bool)$this->redis->set($sessKey, $sessionData, $this->expireTime);
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(string $sessionId): bool
    {
        return (int)$this->redis->del($sessionId) === 1;
    }

    /**
     * {@inheritDoc}
     */
    public function open(string $savePath, string $name): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function gc(int $maxLifetime): bool
    {
        return true;
    }

    /**
     * @param Pool $redis
     */
    public function setRedis(Pool $redis): void
    {
        $this->redis = $redis;
    }
}
