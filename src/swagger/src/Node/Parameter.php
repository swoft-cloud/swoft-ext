<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Parameter
{
    /**
     * Query
     */
    public const QUERY = 'query';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $in = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var bool
     */
    protected $deprecated = false;

    /**
     * @var bool
     */
    protected $allowEmptyValue = false;
}