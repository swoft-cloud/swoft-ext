<?php declare(strict_types=1);


namespace Swoft\Limiter\Rate;

use Redis as RedisPool;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Redis\Redis;

/**
 * Class RedisRateLimiter
 *
 * @since 2.0
 *
 * @Bean()
 */
class RedisRateLimiter extends AbstractRateLimiter
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function getTicket(array $config): bool
    {
        $name = $config['name'];
        $key  = $config['key'];

        $now  = time();
        $sKey = $this->getStorekey($name, $key);
        $nKey = $this->getNextTimeKey($name, $key);

        $rate    = $config['rate'];
        $max     = $config['max'];
        $default = $config['default'];

        $isPermited = true;
        Redis::transaction(function (RedisPool $redis) use ($now, $sKey, $nKey, $rate, $max, $default, &$isPermited) {
            $nextTime      = (int)$redis->get($nKey);
            $storedPermits = (int)$redis->get($sKey);// Init
            if ($nextTime == 0) {
                $storedPermits = $default;
            }
            $nextTime = ($nextTime == 0) ? $now : $nextTime;
            if ($nextTime != 0 && $now > $nextTime) {
                $newPermits    = ($now - $nextTime) * $rate;
                $storedPermits = min($newPermits + $storedPermits, $max);
            }
            if ($storedPermits > 0) {
                $storedPermits = $storedPermits - 1;
                $isPermited    = true;
            }
            $redis->set($nKey, $now);
            $redis->set($sKey, $storedPermits);
        });

        return $isPermited;
    }

    /**
     * @param string $name
     *
     * @param string $key
     *
     * @return string
     */
    private function getNextTimeKey(string $name, string $key): string
    {
        return sprintf('%s:%s:next', $name, $key);
    }

    /**
     * @param string $name
     *
     * @param string $key
     *
     * @return string
     */
    private function getStorekey(string $name, string $key): string
    {
        return sprintf('%s:%s:store', $name, $key);
    }
}