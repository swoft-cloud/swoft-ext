<?php declare(strict_types=1);

namespace Swoft\Whoops;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Throwable;
use function bean;
use function context;

/**
 * Class WhoopsMiddleware
 *
 * @Bean()
 */
class WhoopsMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface        $handler
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $whoops   = bean(WhoopsHandler::class);
            $content  = $whoops->run($e, $request);
            $response = context()->getResponse();

            return $response->withContent($content)->withContentType(ContentType::HTML);
        }
    }
}
