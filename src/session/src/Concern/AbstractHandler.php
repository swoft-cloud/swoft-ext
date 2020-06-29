<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session\Concern;

use Swoft\Contract\EncrypterInterface;
use Swoft\Http\Session\Contract\SessionHandlerInterface;

/**
 * Class AbstractHandler
 *
 * @since 2.0.7
 */
abstract class AbstractHandler implements SessionHandlerInterface
{
    /**
     * The prefix for session key
     *
     * @var string
     */
    protected $prefix = 'sess_';

    /**
     * @var bool
     */
    private $encrypt = false;

    /**
     * TODO The encrypter instance. for encrypt session data
     *
     * @var EncrypterInterface
     */
    protected $encrypter;

    /**
     * The default expire time. 15 mins
     *
     * @var int
     */
    protected $expireTime = 900;

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEncrypt(): bool
    {
        return $this->encrypt;
    }

    /**
     * @param bool $encrypt
     */
    public function setEncrypt(bool $encrypt): void
    {
        $this->encrypt = $encrypt;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function getSessionKey(string $sessionId): string
    {
        return $this->prefix . $sessionId;
    }

    /**
     * @return int
     */
    public function getExpireTime(): int
    {
        return $this->expireTime;
    }

    /**
     * @param int $expireTime
     */
    public function setExpireTime(int $expireTime): void
    {
        if ($expireTime > 1) {
            $this->expireTime = $expireTime;
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
