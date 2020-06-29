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

/**
 * Class PathItem
 *
 * @since 2.0
 */
class PathItem extends Node
{
    /**
     * @var string
     */
    protected $summary = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Operation
     */
    protected $get;

    /**
     * @var Operation
     */
    protected $post;

    /**
     * @var Operation
     */
    protected $put;

    /**
     * @var Operation
     */
    protected $delete;

    /**
     * @var Operation
     */
    protected $options;

    /**
     * @var Operation
     */
    protected $head;

    /**
     * @var Operation
     */
    protected $patch;

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param Operation $get
     */
    public function setGet(Operation $get): void
    {
        $this->get = $get;
    }

    /**
     * @param Operation $post
     */
    public function setPost(Operation $post): void
    {
        $this->post = $post;
    }

    /**
     * @param Operation $put
     */
    public function setPut(Operation $put): void
    {
        $this->put = $put;
    }

    /**
     * @param Operation $delete
     */
    public function setDelete(Operation $delete): void
    {
        $this->delete = $delete;
    }

    /**
     * @param Operation $options
     */
    public function setOptions(Operation $options): void
    {
        $this->options = $options;
    }

    /**
     * @param Operation $head
     */
    public function setHead(Operation $head): void
    {
        $this->head = $head;
    }

    /**
     * @param Operation $patch
     */
    public function setPatch(Operation $patch): void
    {
        $this->patch = $patch;
    }
}
