<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger\Command;

use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Swagger\Swagger;

/**
 * Class SwaggerCommand
 *
 * @since 2.0
 *
 * @Command(name="swg")
 */
class SwaggerCommand
{
    /**
     * @Inject()
     *
     * @var Swagger
     */
    private $swagger;

    /**
     * @CommandMapping(name="g")
     */
    public function gen(): void
    {
        $this->swagger->gen();
    }
}
