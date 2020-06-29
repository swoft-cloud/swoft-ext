<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session;

use Exception;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Cookie;
use Swoft\Http\Session\Concern\AbstractHandler;
use Swoft\Http\Session\Contract\SessionHandlerInterface;
use Swoft\Stdlib\Helper\Str;
use Swoole\Coroutine;
use function array_merge;
use function function_exists;

/**
 * Class SessionManager
 *
 * @Bean("sessionManager")
 */
class SessionManager
{
    /**
     * @var string
     */
    private $name = HttpSession::SESSION_NAME;

    /**
     * @var bool
     */
    private $enable = true;

    /**
     * The max lifetime for sessions GC
     *
     * @var int
     */
    private $lifetime = 1800;

    /**
     * @var array
     * @see Cookie::DEFAULTS
     */
    protected $cookieParams = [
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httpOnly' => true,
        'expires'  => 43200,
        // 'autoRefresh' => false,
    ];

    /**
     * The session handler
     *
     * @var AbstractHandler|SessionHandlerInterface
     */
    private $handler;

    /**
     * @param string $prefix
     *
     * @return string
     * @throws Exception
     */
    public function createSid(string $prefix = ''): string
    {
        if (function_exists('session_create_id')) {
            return session_create_id($prefix);
        }

        return $prefix . Str::randomToken();
    }

    /**
     * Open session
     *
     * @return bool
     */
    public function open(): bool
    {
        if ($this->enable) {
            return $this->handler->open('', $this->name);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        if ($this->enable) {
            return $this->handler->close();
        }

        return false;
    }

    /**
     * Create session instance
     *
     * @param string $sessionId
     *
     * @return HttpSession
     */
    public function createSession(string $sessionId): HttpSession
    {
        return HttpSession::new($sessionId, $this->handler);
    }

    /**
     * @param HttpSession $session
     */
    public function storageSession(HttpSession $session): void
    {
        $sessionData = $session->toString();

        $this->handler->write($session->getSessionId(), $sessionData);
    }

    /**
     * Async garbage collection
     */
    public function asyncGc(): void
    {
        Coroutine::create(function (): void {
            $this->handler->gc($this->lifetime);
        });
    }

    /**
     * Garbage collection
     */
    public function gc(): void
    {
        $this->handler->gc($this->lifetime);
    }

    /*************************************************************
     * Getter, Setter
     ************************************************************/

    /**
     * @return AbstractHandler|SessionHandlerInterface
     */
    public function getHandler(): SessionHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param SessionHandlerInterface $handler
     */
    public function setHandler(SessionHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * @param array $cookieParams
     */
    public function setCookieParams(array $cookieParams): void
    {
        $this->cookieParams = array_merge($this->cookieParams, $cookieParams);
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }
}
