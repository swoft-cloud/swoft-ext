<?php declare(strict_types=1);

namespace Swoft\Session\Handler;

use Swoft\Session\Concern\AbstractHandler;
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
    private $savePath = '/tmp';

    /**
     * {@inheritDoc}
     */
    public function open(string $savePath, string $sessionName): bool
    {
        if (!is_dir($this->savePath)) {
            Dir::make($this->savePath);
        }

        $this->savePath = $savePath;
        return true;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function read(string $id): string
    {
        return (string)file_get_contents($this->getSessionFile($id));
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write(string $id, string $data):bool
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
            @unlink($file);
        }

        return true;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc(int $maxLifetime): bool
    {
        foreach (glob("{$this->savePath}/{$this->prefix}*") as $file) {
            if (file_exists($file) && (filemtime($file) + $maxLifetime) < time()) {
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
    public function getSessionFile(string $id): string
    {
        return $this->savePath . '/' . $this->prefix . $id;
    }
}
