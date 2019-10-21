<?php declare(strict_types=1);

namespace Swoft\Http\Session;

class SessionManager
{
    /**
     * The session handler class or bean name
     *
     * @var string
     */
    private $handler = '';

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * @param string $handler
     */
    public function setHandler(string $handler): void
    {
        $this->handler = $handler;
    }
}
