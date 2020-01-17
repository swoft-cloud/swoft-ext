<?php declare(strict_types=1);


namespace Swoft\Swoole\Tracker\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Swoole\Tracker\SwooleTracker;
use SwooleTracker\Tick;
use Throwable;
use function config;
use function current;
use function defined;
use function swoole_get_local_ip;

/**
 * @since 2.6
 *
 * @Bean()
 */
class SwooleTrackerMiddleware implements MiddlewareInterface
{
    /**
     * @Inject()
     *
     * @var SwooleTracker
     */
    private $swooleTracker;

    /**
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface        $handler
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUriPath();
        if ($path === '/favicon.ico') {
            return $handler->handle($request);
        }

        $tick = $this->startAnalysis($request);

        try {
            // Handle Request
            $response = $handler->handle($request);

            $this->endNormalAnalysis($tick, $response->getStatusCode());
        } catch (Throwable $e) {

            $this->endExceptionAnalysis($tick, $e->getCode());

            throw $e;
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Tick|null
     * @throws Throwable
     */
    private function startAnalysis(Request $request)
    {
        $path    = $request->getUriPath();
        $ip      = current(swoole_get_local_ip());
        $appName = defined('APP_NAME') ? APP_NAME : config('app_name', $ip);

        $traceId = context()->get('traceid', $request->getHeaderLine('traceid'));
        $spanId  = context()->get('spanid', $request->getHeaderLine('spanid'));

        $tick = $this->swooleTracker->startRpcAnalysis($path, $appName, $ip, $traceId, $spanId);

        return $tick;
    }

    /**
     * @param Tick|null $tick
     * @param int       $responseCode
     *
     * @return void
     */
    private function endNormalAnalysis($tick, int $responseCode): void
    {
        if (isset($tick)) {
            $this->swooleTracker->endRpcAnalysis($tick, $responseCode === 200, $responseCode);
        }
    }

    /**
     * @param Tick|null $tick
     * @param int       $errno
     *
     * @return void
     */
    private function endExceptionAnalysis($tick, int $errno): void
    {
        if (isset($tick)) {
            $this->swooleTracker->endRpcAnalysis($tick, false, $errno);
        }
    }
}
