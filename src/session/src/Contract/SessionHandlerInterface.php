<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session\Contract;

/**
 * Class SessionHandlerInterface
 *
 * @see   \SessionHandlerInterface
 * @since 2.0.7
 */
interface SessionHandlerInterface
{
    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * Initialize session
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.open.php
     *
     * @param string $savePath The path where to store/retrieve the session.
     * @param string $name     The session name.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function open(string $savePath, string $name): bool;

    /**
     * Close the session, you can save data.
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function close(): bool;

    /**
     * Read session data
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.read.php
     *
     * @param string $sessionId The session id to read data for.
     *
     * @return string
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     */
    public function read(string $sessionId): string;

    /**
     * Write session data
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.write.php
     *
     * @param string $sessionId   The session id.
     * @param string $sessionData The encoded session data. This data is the
     *                            result of the PHP internally encoding
     *                            the $_SESSION super-global to a serialized
     *                            string and passing it as this parameter.
     *                            Please note sessions use an alternative serialization method.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function write(string $sessionId, string $sessionData): bool;

    /**
     * Destroy a session
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.destroy.php
     *
     * @param string $sessionId The session ID being destroyed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $sessionId): bool;

    /**
     * Cleanup old sessions
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.gc.php
     *
     * @param int $maxLifetime Sessions that have not updated for
     *                         the last max lifetime seconds will be removed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function gc(int $maxLifetime): bool;
}
