<?php declare(strict_types=1);

namespace Swoft\Session\Handler;

use Swoft\Session\Concern\AbstractHandler;
use Swoft\Swlib\MemTable;
use Swoole\Table;
use function time;

/**
 * Class MemTableHandler
 *
 * @since 2.0.7
 */
class MemTableHandler extends AbstractHandler
{
    public const TIME_FIELD = 'ctime';
    public const DATA_FIELD = 'data';

    /**
     * @var MemTable
     */
    private $table;

    /**
     * Close the session
     *
     * @link  https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function close(): bool
    {
        return true;
    }

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
    public function destroy(string $sessionId): bool
    {
        return $this->table->del($sessionId);
    }

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
    public function gc(int $maxLifetime): bool
    {
        $expireTime = time() - $maxLifetime;

        $this->table->each(function (array $row) use ($expireTime) {
            $ctime = $row[self::TIME_FIELD];

            if ($ctime < $expireTime) {
                $this->table->del($row[MemTable::KEY_FIELD]);
            }
        });

        return true;
    }

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
    public function open(string $savePath, string $name): bool
    {
        $this->table = new MemTable($name, 10240, [
            self::TIME_FIELD => [Table::TYPE_INT, 10],
            self::DATA_FIELD => [Table::TYPE_STRING, 10240],
        ]);

        return $this->table->create();
    }

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
    public function read(string $sessionId): string
    {
        /** @var string|false $data */
        $data = $this->table->get($sessionId, self::DATA_FIELD);

        return $data === false ? '' : $data;
    }

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
    public function write(string $sessionId, string $sessionData): bool
    {
        return $this->table->set($sessionId, [
            self::TIME_FIELD => time(),
            self::DATA_FIELD => $sessionData,
        ]);
    }
}
