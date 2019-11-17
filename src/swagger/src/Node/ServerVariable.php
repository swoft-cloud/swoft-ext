<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class ServerVariable
 *
 * @since 2.0
 */
class ServerVariable
{
    /**
     * @var array
     */
    protected $enum = [];

    /**
     * @var string
     */
    protected $default = '';

    /**
     * @var string
     */
    protected $description = '';
}