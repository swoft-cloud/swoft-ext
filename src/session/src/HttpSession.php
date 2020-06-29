<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Session;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use RuntimeException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Http\Session\Contract\SessionHandlerInterface;
use Swoft\Stdlib\Helper\PhpHelper;
use function array_merge;
use function bean;
use function context;
use function time;

/**
 * Class Session
 *
 * @since 2.0.7
 * @Bean(scope=Bean::PROTOTYPE)
 */
class HttpSession implements ArrayAccess, SessionInterface, IteratorAggregate
{
    // use DataPropertyTrait;

    /**
     * Key name for storage cookies/headers
     */
    public const SESSION_NAME = 'SWOFT_SESSION_ID';

    /**
     * Key name for storage context
     */
    public const CONTEXT_KEY = '__SWOFT_SESSION';

    /**
     * Flash data key name
     */
    public const FLASH_KEY = '__FLASH';

    public const FLASH_OLD = '__FLASH_OLD';

    public const FLASH_NEW = '__FLASH_NEW';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Mark session is destroyed
     *
     * @var bool
     */
    private $closed = false;

    /**
     * @var SessionHandlerInterface
     */
    private $handler;

    /**
     * @var string
     */
    private $sessionId = '';

    /**
     * @param string                  $sessionId
     * @param SessionHandlerInterface $handler
     *
     * @return static
     */
    public static function new(string $sessionId, SessionHandlerInterface $handler): self
    {
        /** @var self $self */
        $self = bean(static::class);

        // Initial properties
        $self->sessionId = $sessionId;
        $self->handler   = $handler;

        return $self;
    }

    /**
     * @param array $data
     *
     * @return SessionInterface|self
     */
    public static function newFromArray(array $data): SessionInterface
    {
        /** @var self $self */
        $self = bean(static::class);

        // Initial properties
        $self->sessionId = $data['sessionId'];
        // Reset data
        $self->data = $data['data'];

        return $self;
    }

    /**
     * @return static
     */
    public static function current(): self
    {
        if (context()->has(self::CONTEXT_KEY)) {
            return context()->get(self::CONTEXT_KEY);
        }

        throw new RuntimeException('http session is not started');
    }

    /**
     * Load session data from handler
     */
    public function loadData(): void
    {
        $sessionData = $this->handler->read($this->sessionId);

        if ($sessionData) {
            $this->data = PhpHelper::unserialize($sessionData);
        }
    }

    /**
     * Save new session data
     */
    public function saveData(): bool
    {
        if (!$this->closed) {
            $sessionData = PhpHelper::serialize($this->data);

            return $this->handler->write($this->sessionId, $sessionData);
        }

        return false;
    }

    /*************************************************************
     * Session data operate
     ************************************************************/

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // Load latest data
        $this->loadData();

        return $this->data[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        // Load latest data
        $this->loadData();

        $this->data[$key] = $value;

        // Save new session data
        return $this->saveData();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function setMulti(array $data): bool
    {
        // Load latest data
        $this->loadData();

        $this->data = array_merge($this->data, $data);

        // Save new session data
        return $this->saveData();
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        // Load latest data
        $this->loadData();

        return isset($this->data[$key]);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        // Load latest data
        $this->loadData();

        // Delete and rewrite
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            return $this->saveData();
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function remove(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        $this->data   = [];
        $this->closed = true;

        return $this->handler->destroy($this->sessionId);
    }

    /**
     * Clear all data
     */
    public function clear(): void
    {
        $this->data = [];
        $this->handler->write($this->sessionId, '');
    }

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return !$this->closed;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $this->loadData();

        return $this->data;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->handler->read($this->sessionId);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    public function getData(bool $refresh = false): array
    {
        if ($refresh) {
            $this->loadData();
        }

        return $this->data;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param array $cookie
     *
     * @return array
     */
    public function buildCookie(array $cookie): array
    {
        $cookie['value'] = $this->getSessionId();

        if ($this->isClosed()) {
            $cookie['value']   = '';
            $cookie['expires'] = -60;
        } elseif (isset($cookie['expires']) && $cookie['expires'] > 0) {
            $cookie['expires'] += time();
        }

        return $cookie;
    }

    /*************************************************************
     * Flash data operate
     ************************************************************/

    /**
     * Set flash message.
     * - can only be used once after storage, ie will be deleted after the first acquisition
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setFlash(string $name, $value): void
    {
        // Load latest data
        $this->loadData();

        // Add flash value
        $this->data[self::FLASH_NEW][$name] = $value;

        // Save new session data
        $this->saveData();
    }

    /**
     * Get flash message. (Will be deleted after getting it)
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getFlash(string $name, $default = null)
    {
        // Load latest data
        $this->loadData();

        // Exist flash value
        if (isset($this->data[self::FLASH_NEW][$name])) {
            $value = $this->data[self::FLASH_NEW][$name];

            $this->data[self::FLASH_OLD][$name] = $value;

            // Save new session data.
            $this->saveData();

            return $value;
        }

        return $default;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function setFlashes(array $data): void
    {
        // Load latest data
        $this->loadData();

        $old = $this->data[self::FLASH_NEW];

        $this->data[self::FLASH_NEW] = $data;
        $this->data[self::FLASH_OLD] = $old;

        // Save new session data.
        $this->saveData();
    }

    /**
     * @return array
     */
    public function getFlashes(): array
    {
        // Load latest data
        $this->loadData();

        return $this->data[self::FLASH_NEW] ?? [];
    }

    /**
     * @return void
     */
    public function clearFlashes(): void
    {
        // Load latest data
        $this->loadData();

        unset($this->data[self::FLASH_NEW], $this->data[self::FLASH_OLD]);

        // Save new session data
        $this->saveData();
    }

    /*************************************************************
     * Array access operate
     ************************************************************/

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    /**
     * Defined by IteratorAggregate interface
     * Returns an iterator for this object, for use with foreach
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getData());
    }
}
