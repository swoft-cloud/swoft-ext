<?php declare(strict_types=1);

namespace Swoft\Whoops;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Throwable;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;
use Whoops\Run;

/**
 * Class WhoopsHandler
 *
 * @Bean()
 */
class WhoopsHandler
{
    /**
     * @param Throwable $e
     * @param Request   $request
     *
     * @return string
     */
    public function run(Throwable $e, Request $request): string
    {
        $whoops = $this->createWhoops($request);
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);

        return $whoops->handleException($e);
    }

    /**
     * @param Request $request
     *
     * @return Run
     */
    public function createWhoops(Request $request): Run
    {
        $whoops = new Run();
        if (!$cTypes = $request->getHeader(ContentType::KEY)) {
            return $whoops->appendHandler($this->createPageHandler());
        }

        $format = array_search($cTypes[0], ContentType::TYPES, true);
        switch ($format) {
            case 'json':
                $handler = new JsonResponseHandler();
                $handler->addTraceToOutput(true);
                break;
            case 'html':
                $handler = $this->createPageHandler();
                break;
            case 'text':
                $handler = new PlainTextHandler();
                $handler->addTraceToOutput(true);
                break;
            case 'xml':
                $handler = new XmlResponseHandler();
                $handler->addTraceToOutput(true);
                break;
            default:
                if ($format) {
                    $handler = new PlainTextHandler;
                    $handler->addTraceToOutput(true);
                } else {
                    $handler = $this->createPageHandler();
                }
        }

        $whoops->appendHandler($handler);
        return $whoops;
    }

    /**
     * @return PrettyPageHandler
     */
    private function createPageHandler(): PrettyPageHandler
    {
        $pph = new PrettyPageHandler();
        $pph->handleUnconditionally(true);

        return $pph;
    }
}
