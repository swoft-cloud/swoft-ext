<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Server
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var ServerVariable[]
     */
    protected $variables = [];
}