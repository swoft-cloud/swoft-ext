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

use RuntimeException;
use Swoft\Co;
use function extension_loaded;

/**
 * Class CoFileHandler
 *
 * @since 2.0.7
 */
class CoFileHandler extends FileHandler
{
    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return extension_loaded('swoole');
    }

    /**
     * @param string $id
     *
     * @return string
     * @throws RuntimeException
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

        return Co::readFile($file);
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write(string $id, string $data): bool
    {
        return Co::writeFile($this->getSessionFile($id), $data) !== false;
    }
}
