<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\Context\Context;
use Swoft\Http\Message\Response;
use Swoft\View\Renderer;

if (!function_exists('view')) {
    /**
     * @param string            $template
     * @param array             $data
     * @param string|null|false $layout
     *
     * @return Response
     * @throws Throwable
     */
    function view(string $template, array $data = [], $layout = null)
    {
        /**
         * @var Renderer $renderer
         * @var Response $response
         */
        $renderer = Swoft::getSingleton('view');
        $response = Context::mustGet()->getResponse();
        $content  = $renderer->render(Swoft::getAlias($template), $data, $layout);

        return $response->withContent($content)->withHeader('Content-Type', 'text/html');
    }
}
