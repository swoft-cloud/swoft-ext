<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger\Node;

class Server extends Node
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

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param ServerVariable[] $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }
}
