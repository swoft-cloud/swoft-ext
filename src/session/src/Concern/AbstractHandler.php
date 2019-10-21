<?php declare(strict_types=1);

namespace Swoft\Session\Concern;

use Swoft\Session\Contract\SessionHandlerInterface;

/**
 * Class AbstractHandler
 *
 * @since 2.0.7
 */
abstract class AbstractHandler implements SessionHandlerInterface
{
    /**
     * @var string
     */
    protected $prefix = 'sess_';

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
