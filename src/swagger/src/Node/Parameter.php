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
