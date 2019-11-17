<?php declare(strict_types=1);

namespace Swoft\Http\Session\Handler;

use Swoft\Http\Session\Contract\SessionHandlerInterface;

/**
 * Class EmptyHandler
 */
class EmptyHandler implements SessionHandlerInterface
{
    /**
     * @return bool
     */
    public static function isSupported(): bool
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
    public function destroy(string $id): bool
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
     * {@inheritDoc}
     */
    public function open(string $savePath, string $id): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read(string $id): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $id, string $data): bool
    {
        return true;
    }
}
