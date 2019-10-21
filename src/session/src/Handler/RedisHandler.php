<?php declare(strict_types=1);

namespace Swoft\Session\Handler;

use function extension_loaded;

/**
 * Class RedisHandler
 *
 * @since 2.0.7
 */
class RedisHandler
{
    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return extension_loaded('redis');
    }
}
