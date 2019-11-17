<?php declare(strict_types=1);

namespace Swoft\Http\Session;

use Swoft\Helper\ComposerJSON;
use Swoft\Http\Session\Handler\FileHandler;
use Swoft\SwoftComponent;
use function alias;
use function bean;
use function dirname;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    public function beans(): array
    {
        return [
            'sessionManager' => [
                'class'   => SessionManager::class,
                'handler' => bean('sessionHandler'),
            ],
            'sessionHandler' => [
                'class'    => FileHandler::class,
                // For storage session files
                'savePath' => alias('@runtime/sessions')
            ],
        ];
    }
}
