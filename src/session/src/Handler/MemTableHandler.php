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
use Swoft\Swlib\MemTable;
use Swoole\Table;
use function class_exists;
use function time;

/**
 * Class MemTableHandler
 *
 * @since 2.0.7
 */
class MemTableHandler extends AbstractHandler
{
    public const TIME_FIELD = 't';

    public const DATA_FIELD = 'd';

    /**
     * @var MemTable
     */
    private $table;

    /**
     * @var string
     */
    private $dataFile = '';

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(Table::class);
    }

    /**
     * {@inheritDoc}
     */
    public function open(string $savePath, string $name): bool
    {
        $this->table = new MemTable($name, 10240, [
            self::TIME_FIELD => [Table::TYPE_INT, 10],
            self::DATA_FIELD => [Table::TYPE_STRING, 10240],
        ]);

        $ok = $this->table->create();
        if ($ok && $this->dataFile) {
            $this->table->setDbFile($this->dataFile);
            $this->table->restore();
        }

        return $ok;
    }

    /**
     * {@inheritDoc}
     */
    public function close(): bool
    {
        if ($this->dataFile) {
            $this->table->dump();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read(string $sessionId): string
    {
        /** @var array|false $row */
        $row = $this->table->get($sessionId);
        if ($row === false) {
            return '';
        }

        // check data expire
        $expireTime = $this->expireTime + $row[self::TIME_FIELD];
        if ($expireTime < time()) {
            $this->table->del($sessionId);
            return '';
        }

        return $row[self::DATA_FIELD];
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $sessionId, string $sessionData): bool
    {
        return $this->table->set($sessionId, [
            self::TIME_FIELD => time(),
            self::DATA_FIELD => $sessionData,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(string $sessionId): bool
    {
        return $this->table->del($sessionId);
    }

    /**
     * {@inheritDoc}
     */
    public function gc(int $maxLifetime): bool
    {
        $expireTime = time() - $maxLifetime;

        $this->table->each(function (array $row) use ($expireTime): void {
            $ctime = $row[self::TIME_FIELD];

            if ($ctime < $expireTime) {
                $this->table->del($row[MemTable::KEY_FIELD]);
            }
        });

        return true;
    }

    /**
     * @return string
     */
    public function getDataFile(): string
    {
        return $this->dataFile;
    }

    /**
     * @param string $dataFile
     */
    public function setDataFile(string $dataFile): void
    {
        $this->dataFile = $dataFile;
    }
}
