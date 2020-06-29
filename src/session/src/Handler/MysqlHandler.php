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

/**
 * Class MysqlHandler TODO
 *
 * @since 2.0.7
 */
class MysqlHandler extends AbstractHandler
{
    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return false;
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
    public function read(string $sessionId): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $sessionId, string $sessionData): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(string $sessionId): bool
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
}
