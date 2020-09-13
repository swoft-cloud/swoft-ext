<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session\Handler;

use Swoft\Http\Session\Concern\AbstractHandler;
use function time;

/**
 * Class ArrayHandler
 *
 * @since 2.0.7
 */
class ArrayHandler extends AbstractHandler
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * {@inheritDoc}
     */
    public function open(string $savePath, string $name): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close(): bool
    {
        // $this->data = [];
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read(string $sessionId): string
    {
        // TODO handle expire
        return $this->data[$sessionId] ?? '';
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $sessionId, string $sessionData): bool
    {
        $this->data[$sessionId] = [
            't' => time(),
            'v' => $sessionData,
        ];

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(string $sessionId): bool
    {
        if (isset($this->data[$sessionId])) {
            unset($this->data[$sessionId]);
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function gc(int $maxLifetime): bool
    {
        $curTime = time();

        foreach ($this->data as $sid => $item) {
            if (($item['t'] + $maxLifetime) < $curTime) {
                unset($this->data[$sid]);
            }
        }

        return true;
    }
}
