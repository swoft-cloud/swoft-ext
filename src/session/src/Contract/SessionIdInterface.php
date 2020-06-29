<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session\Contract;

/**
 * Interface SessionIdInterface
 */
interface SessionIdInterface
{
    /**
     * Create session ID
     *
     * @link https://php.net/manual/en/sessionidinterface.create-sid.php
     * @return string
     */
    public function createSid(): string;
}
