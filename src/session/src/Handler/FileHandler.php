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
use Swoft\Stdlib\Helper\Dir;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function glob;
use function is_dir;
use function time;
use function unlink;

/**
 * Class FileHandler
 */
class FileHandler extends AbstractHandler
{
    /**
     * @var string
     */
    private $savePath = '/tmp/swoft-sessions';

    /**
     * Init $savePath directory
     */
    public function init(): void
    {
        if (!is_dir($this->savePath)) {
            Dir::make($this->savePath);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function read(string $id): string
    {
        $file = $this->getSessionFile($id);
        if (!file_exists($file)) {
            return '';
        }

        // If data has been expired
        if (filemtime($file) + $this->expireTime < time()) {
            unlink($file);
            return '';
        }

        return (string)file_get_contents($file);
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write(string $id, string $data): bool
    {
        return file_put_contents($this->getSessionFile($id), $data) !== false;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $file = $this->getSessionFile($id);
        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc(int $maxLifetime): bool
    {
        $curTime = time();

        foreach (glob("{$this->savePath}/{$this->prefix}*") as $file) {
            if (file_exists($file) && (filemtime($file) + $maxLifetime) < $curTime) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * Close the session, will clear all session data.
     *
     * @return bool
     */
    public function close(): bool
    {
        // return $this->gc(-1);
        return true;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    protected function getSessionFile(string $id): string
    {
        return $this->savePath . '/' . $this->prefix . $id;
    }

    /**
     * @return string
     */
    public function getSavePath(): string
    {
        return $this->savePath;
    }

    /**
     * @param string $savePath
     */
    public function setSavePath(string $savePath): void
    {
        $this->savePath = $savePath;
    }
}
