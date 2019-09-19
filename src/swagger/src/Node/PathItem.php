<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;

/**
 * Class PathItem
 *
 * @since 2.0
 */
class PathItem
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
}