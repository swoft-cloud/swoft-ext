<?php declare(strict_types=1);

namespace Swoft\Session\Handler;

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
        return Co::readFile($this->getSessionFile($id));
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
