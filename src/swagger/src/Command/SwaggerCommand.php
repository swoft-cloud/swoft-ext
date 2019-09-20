<?php declare(strict_types=1);


namespace Swoft\Swagger\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;

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
     * @CommandMapping(name="g")
     */
    public function gen(): void
    {

    }
}