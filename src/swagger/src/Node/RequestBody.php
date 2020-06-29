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

class RequestBody extends Node
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var MediaType[]
     */
    protected $content;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param MediaType[] $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
}
