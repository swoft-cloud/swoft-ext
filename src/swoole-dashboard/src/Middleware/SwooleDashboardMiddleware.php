<?php declare(strict_types=1);


namespace Swoft\Swoole\Dashboard\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Swoole\Dashboard\Logic\SwooleDashboardLogic;
use Throwable;

/**
 * Class SwooleDashboardMiddleware
 *
 * @Bean()
 */
class SwooleDashboardMiddleware implements MiddlewareInterface
{

    /**
     * @Inject()
     *
     * @var SwooleDashboardLogic
     */
    private $swoleDashboardLogic;

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
        $this->swoleDashboardLogic->startAnalysis();

        // Handle Request
        $response = $handler->handle($request);

        // After request
        $this->swoleDashboardLogic->endAnalysis();
        return $response;
    }
}
