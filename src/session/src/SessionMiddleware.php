<?php declare(strict_types=1);

namespace Swoft\Http\Session;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Cookie;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoole\Coroutine;
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
        // If not enabled session
        if (!$this->manager->isEnable()) {
            return $handler->handle($request);
        }

        // Start and create session
        $session = $this->startSession($request);

        // Storage to context
        context()->set(HttpSession::CONTEXT_KEY, $session);

        // Garbage collection
        $this->collectGarbage();

        // Processing ...
        $response = $handler->handle($request);

        // Add cookie to response
        $this->addCookieToResponse($response, $session);

        // $this->storageSession($session);
        $session->saveData();

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
     * param HttpSession $session
     *
     * @return void
     * @throws Exception
     */
    protected function collectGarbage(): void
    {
        $max = $this->randomMax;

        // Gc handle on new coroutine
        if (random_int(1, $max) === round($max / 2)) {
            Coroutine::create(function () {
                $this->manager->gc();
            });
        }
    }

    /**
     * @param ResponseInterface|Response $response
     * @param HttpSession                $session
     */
    private function addCookieToResponse(ResponseInterface $response, HttpSession $session): void
    {
        $cookie = Cookie::new($this->manager->getCookieParams());
        $cookie->setValue($session->getSessionId());

        $response->setCookie($this->manager->getName(), $cookie);
    }
}
