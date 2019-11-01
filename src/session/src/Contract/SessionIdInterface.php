<?php declare(strict_types=1);

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
