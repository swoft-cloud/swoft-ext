<?php declare(strict_types=1);

namespace Swoft\Session\Handler;

use Swoft\Session\Contract\SessionHandlerInterface;

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
     * Close the session
     *
     * @return bool The return value (usually TRUE on success, FALSE on failure).
     *              Note this value is returned internally to PHP for processing.
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * @param string $id The session ID being destroyed.
     *
     * @return  bool  The return value (usually TRUE on success, FALSE on failure).
     *                Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $id): bool
    {
        return true;
    }

    /**
     * Cleanup old sessions
     *
     * @param int $maxLifetime     Sessions that have not updated for
     *                             the last maxlifetime seconds will be removed.
     *
     * @return  bool  The return value (usually TRUE on success, FALSE on failure).
     *                Note this value is returned internally to PHP for processing.
     */
    public function gc(int $maxLifetime): bool
    {
        return true;
    }

    /**
     * Initialize session
     *
     * @param string $savePath The path where to store/retrieve the session.
     * @param string $id       The session id.
     *
     * @return  bool  The return value (usually TRUE on success, FALSE on failure).
     *                Note this value is returned internally to PHP for processing.
     */
    public function open(string $savePath, string $id): bool
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id The session id to read data for.
     *
     * @return  string  Returns an encoded string of the read data.
     *                  If nothing was read, it must return an empty string.
     *                  Note this value is returned internally to PHP for processing.
     */
    public function read(string $id): string
    {
        return '';
    }

    /**
     * Write session data
     *
     * @param string $id       The session id.
     * @param string $data     The encoded session data. This data is the
     *                         result of the PHP internally encoding
     *                         the $_SESSION super global to a serialized
     *                         string and passing it as this parameter.
     *                         Please note sessions use an alternative serialization method.
     *
     * @return   bool  The return value (usually TRUE on success, FALSE on failure).
     *                 Note this value is returned internally to PHP for processing.
     */
    public function write(string $id, string $data): bool
    {
        return true;
    }
}
