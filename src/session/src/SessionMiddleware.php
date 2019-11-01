<?php declare(strict_types=1);

namespace Swoft\Http\Session;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use function context;
use function random_int;
use function round;

/**
 * Class SessionMiddleware
 * - refer laravel/session, aura/session, psr7-sessions/storageless
 *
 * @since 2.0.7
 * @Bean()
 */
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var bool
     */
    private $enable = false;

    /**
     * @var int
     */
    private $randomMax = 10;

    /**
     * @var SessionManager
     */
    private $manager;

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request   $request
     * @param RequestHandlerInterface|Response $handler
     *
     * @return ResponseInterface
     * @throws SwoftException
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // If not enabled
        if (!$this->enable) {
            return $handler->handle($request);
        }

        // Start and create session
        $session = $this->startSession($request);

        // Storage to context
        context()->set(HttpSession::CONTEXT_KEY, $session);

        // Garbage collection
        $this->collectGarbage($session);

        // Processing ...
        $response = $handler->handle($request);

        $this->addCookieToResponse($response, $session);

        $this->storageSession();

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return HttpSession
     */
    protected function startSession(Request $request): HttpSession
    {
        /** @var SessionManager $manager */
        // $manager = bean('sessionManager');
        $keyName = $this->manager->getName();

        // Find from cookies
        if (!$sessionId = $request->cookie($keyName, '')) {
            // Or find from header
            $sessionId = $request->getHeaderLine($keyName);
        }

        // Session::bindCo($sessionId);
        return $this->manager->createSession($sessionId);
    }

    /**
     * Remove the garbage from the session if necessary.
     *
     * @param HttpSession $session
     *
     * @return void
     * @throws Exception
     */
    protected function collectGarbage(HttpSession $session): void
    {
        $max = $this->randomMax;

        if (random_int(1, $max) === round($max / 2)) {
            $this->manager->gc();
        }
    }

    /**
     * @param HttpSession $session
     */
    protected function storageSession(HttpSession $session): void
    {
        $this->manager->storageSession($session);
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
}
