<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Swoole\Dashboard\SwooleDashboard;
use Throwable;
use function config;
use function current;
use function defined;
use function swoole_get_local_ip;

/**
 * Class SwooleDashboardMiddleware
 *
 * @Bean()
 * @since 2.0
 */
class SwooleDashboardMiddleware implements MiddlewareInterface
{

    /**
     * Swoole dashboard tick
     */
    public const SWOOLE_DASHBOARD_TICK = 'swooleashboardDTick';

    /**
     * @Inject("swooleDashboard")
     *
     * @var SwooleDashboard
     */
    private $swoleDashboard;

    /**
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface        $handler
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUriPath() === '/favicon.ico') {
            return $handler->handle($request);
        }

        $request = $this->startAnalysis($request);

        try {
            // Handle Request
            $response = $handler->handle($request);

            $this->endNormalAnalysis($request, $response);
        } catch (Throwable $e) {
            $this->endExceptionAnalysis($request, $e);

            throw $e;
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface|Request $request
     *
     * @return ServerRequestInterface
     * @throws Throwable
     */
    private function startAnalysis(ServerRequestInterface $request): ServerRequestInterface
    {
        // Before request
        $this->swoleDashboard->startAnalysis();

        if ($this->swoleDashboard->isLinkTracking()) {
            $ip      = current(swoole_get_local_ip());
            $appName = defined('APP_NAME') ? APP_NAME : config('app_name', $ip);
            $traceId = context()->get('traceid', '');
            $spanId  = context()->get('spanid', '');

            $tick = $this->swoleDashboard->startRpcAnalysis($request->getUriPath(), $appName, $ip, $traceId, $spanId);

            return $request->withAttribute(self::SWOOLE_DASHBOARD_TICK, $tick);
        }

        return $request;
    }

    /**
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface|Response     $response
     *
     * @return void
     */
    private function endNormalAnalysis(ServerRequestInterface $request, ResponseInterface $response): void
    {
        $tick = $request->getAttribute(self::SWOOLE_DASHBOARD_TICK);
        if (isset($tick)) {
            $this->swoleDashboard->endRpcAnalysis(
                $tick,
                $response->getStatusCode() === 200,
                $response->getStatusCode()
            );
        }

        // After request
        $this->swoleDashboard->endAnalysis();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable              $throwable
     *
     * @return void
     */
    private function endExceptionAnalysis(ServerRequestInterface $request, Throwable $throwable): void
    {
        $tick = $request->getAttribute(self::SWOOLE_DASHBOARD_TICK);
        if (isset($tick)) {
            $this->swoleDashboard->endRpcAnalysis(
                $tick,
                false,
                $throwable->getCode()
            );
        }

        // After request
        $this->swoleDashboard->endAnalysis();
    }
}
