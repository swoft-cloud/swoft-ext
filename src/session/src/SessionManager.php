<?php declare(strict_types=1);

namespace Swoft\Http\Session;

use Exception;
use RuntimeException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Contract\HandlerInterface;
use Swoft\Exception\SwoftException;
use Swoft\Http\Session\Concern\AbstractHandler;
use Swoft\Stdlib\Helper\Str;
use function array_merge;
use function context;
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
    private $enable = false;

    /**
     * @var array
     */
    protected $cookieParams = [
        'path'        => '/',
        'domain'      => null,
        'secure'      => false,
        'httpOnly'    => true,
        // 'lifetime'    => 86400,
        // 'autoRefresh' => false,
    ];

    /**
     * The session handler class or bean name
     *
     * @var AbstractHandler|HandlerInterface
     */
    private $handler;

    /**
     * @param string $prefix
     *
     * @return string
     * @throws Exception
     */
    public function createSid(string $prefix = 'sess_'): string
    {
        if (function_exists('session_create_id')) {
            return session_create_id($prefix);
        }

        return $prefix . Str::randomToken();
    }

    /**
     * @return HttpSession
     * @throws SwoftException
     */
    public function getSession(): HttpSession
    {
        if (context()->has(HttpSession::CONTEXT_KEY)) {
            return context()->get(HttpSession::CONTEXT_KEY);
        }

        throw new RuntimeException('http session instance is not exists');
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
     * Garbage collection
     */
    public function gc(): void
    {
        $this->handler->gc($this->handler->getExpireTime());
    }

    /*************************************************************
     * Getter, Setter
     ************************************************************/

    /**
     * @return AbstractHandler|HandlerInterface
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param HandlerInterface $handler
     */
    public function setHandler(HandlerInterface $handler): void
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
}
