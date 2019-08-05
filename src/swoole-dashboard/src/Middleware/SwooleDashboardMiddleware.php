<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Swoole\Dashboard\SwooleDashboardManager;
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
     * @Inject()
     *
     * @var SwooleDashboardManager
     */
    private $swoleDashboardManager;

    /**
     * @Inject()
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
        // Before request
        $this->swoleDashboardManager->startAnalysis();

        if ($this->swoleDashboard->isLinkTracking()) {
            $ip = current(swoole_get_local_ip());

            $traceId = context()->get('traceid', '');
            $spanId  = context()->get('spanid', '');

            $tick = $this->swoleDashboardManager->startRpcAnalysis(
                $request->getUriPath(),
                defined('APP_NAME') ? APP_NAME : config('app_name'),
                $ip,
                $traceId,
                $spanId
            );
        }

        try {
            // Handle Request
            $response = $handler->handle($request);

            // After request
            $this->swoleDashboardManager->endAnalysis();

            if (isset($tick)) {
                $this->swoleDashboardManager->endRpcAnalysis(
                    $tick,
                    $response->getStatusCode() == 200,
                    $response->getStatusCode()
                );
            }

            return $response;
        } catch (Throwable $e) {
            if (isset($tick)) {
                $this->swoleDashboardManager->endRpcAnalysis($tick, false, $e->getCode());
            }

            throw $e;
        }
    }
}
