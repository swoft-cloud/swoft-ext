<?php declare(strict_types=1);

namespace Swoft\Http\Session;

class Session
{
    public function createSid(string $prefix = 'sess_'): string
    {
        return session_create_id($prefix);
    }
}
