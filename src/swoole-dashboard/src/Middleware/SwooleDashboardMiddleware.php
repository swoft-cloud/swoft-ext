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
        $path = $request->getUriPath();
        if ($path === '/favicon.ico') {
            return $handler->handle($request);
        }

        $tick = $this->startAnalysis($path);

        try {
            // Handle Request
            $response = $handler->handle($request);

            $this->endNormalAnalysis($tick, $response);
        } catch (Throwable $e) {
            $this->endExceptionAnalysis($tick, $e);

            throw $e;
        }

        return $response;
    }

    /**
     * @param string $path
     *
     * @return object|null
     * @throws Throwable
     */
    private function startAnalysis(string $path): ?object
    {
        // Before request
        $this->swoleDashboard->startAnalysis();

        if ($this->swoleDashboard->isLinkTracking()) {
            $ip      = current(swoole_get_local_ip());
            $appName = defined('APP_NAME') ? APP_NAME : config('app_name', $ip);
            $traceId = context()->get('traceid', '');
            $spanId  = context()->get('spanid', '');

            $tick = $this->swoleDashboard->startRpcAnalysis($path, $appName, $ip, $traceId, $spanId);

            return $tick;
        }

        return null;
    }

    /**
     * @param object|null                $tick
     * @param ResponseInterface|Response $response
     *
     * @return void
     */
    private function endNormalAnalysis(?object $tick, ResponseInterface $response): void
    {
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
     * @param object|null $tick
     * @param Throwable   $throwable
     *
     * @return void
     */
    private function endExceptionAnalysis(?object $tick, Throwable $throwable): void
    {
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
