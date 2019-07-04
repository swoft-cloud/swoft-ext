<?php declare(strict_types=1);


namespace Swoft\Limiter\Rate;

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

        $lua = <<<LUA
        local now = tonumber(KEYS[1]);
        local sKey = KEYS[2];
        local nKey = KEYS[3];
        local rate = tonumber(KEYS[4]);
        local max = tonumber(KEYS[5]);
        local default = tonumber(KEYS[6]);
        
        local sNum = redis.call('get', sKey);
        sNum = tonumber(sNum);
        if(sNum == nil)
        then
            sNum = 0
        end
        
        local nNum = redis.call('get', nKey);
        nNum = tonumber(nNum);
        if(nNum == nil)
        then
            nNum = now
            sNum = default
        end
        
        local newPermits = 0;
        if(now > nNum)
        then
              newPermits = (now-nNum)*rate+sNum;
              sNum = math.min(newPermits, max)
        end
        
        local isPermited = 0;
        if(sNum > 0)
        then
            sNum = sNum -1;
            isPermited = 1;
        end
        
        redis.call('set', sKey, sNum);
        redis.call('set', nKey, now);
        
        return isPermited;
LUA;

        $args = [
            $now,
            $sKey,
            $nKey,
            $rate,
            $max,
            $default,
        ];

        $result = Redis::eval($lua, $args, count($args));
        return (bool)$result;
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