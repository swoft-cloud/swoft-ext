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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
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
     * Probably interval how often garbage collection
     *
     * @var int
     */
    private $randomMax = 10;

    /**
     * @Inject("sessionManager")
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
        $this->collectGarbage($this->randomMax);

        // Processing ...
        $response = $handler->handle($request);

        // Add cookie to response
        $this->addCookieToResponse($response, $session);

        // $this->storageSession($session);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return HttpSession
     * @throws Exception
     */
    protected function startSession(Request $request): HttpSession
    {
        $keyName = $this->manager->getName();

        // Find from cookies
        if (!$sessionId = $request->cookie($keyName, '')) {
            // Or find from header
            $sessionId = $request->getHeaderLine($keyName);
        }

        // Not exists, will start new session
        if (!$sessionId) {
            $sessionId = $this->manager->createSid();
        }

        // TODO Session::bindCo($sessionId) bind?
        return $this->manager->createSession($sessionId);
    }

    /**
     * Remove the garbage from the session if necessary.
     *
     * @param int $randomMax
     *
     * @return void
     * @throws Exception
     */
    protected function collectGarbage(int $randomMax): void
    {
        // Aways do GC
        if ($randomMax < 2) {
            $this->manager->asyncGc();
        }

        // GC handle on new coroutine
        if (random_int(1, $randomMax) === round($randomMax / 2)) {
            $this->manager->asyncGc();
        }
    }

    /**
     * @param ResponseInterface|Response $response
     * @param HttpSession                $session
     */
    protected function addCookieToResponse(ResponseInterface $response, HttpSession $session): void
    {
        $cookie = $this->manager->getCookieParams();
        $cookie = $session->buildCookie($cookie);

        $response->setCookie($this->manager->getName(), $cookie);
    }
}
