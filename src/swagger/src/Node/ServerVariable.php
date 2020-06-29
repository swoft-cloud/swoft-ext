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
