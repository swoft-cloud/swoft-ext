<?php declare(strict_types=1);

namespace Swoft\Http\Session;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\SessionInterface;
use Swoft\Http\Session\Contract\SessionHandlerInterface;
use Swoft\Stdlib\Helper\JsonHelper;
use function bean;

/**
 * Class Session
 *
 * @since 2.0.7
 * @Bean(scope=Bean::PROTOTYPE)
 */
class HttpSession implements \ArrayAccess, SessionInterface
{
    // use DataPropertyTrait;

    /**
     * Key name for storage cookies/headers/context
     */
    public const SESSION_NAME = 'SWOFT_SESSION_ID';
    public const CONTEXT_KEY  = '_SWOFT_SESSION';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var SessionHandlerInterface
     */
    private $handler;

    /**
     * @var string
     */
    private $sessionId = '';

    /**
     * @param array                   $data
     * @param SessionHandlerInterface $handler
     *
     * @return static
     */
    public static function new(array $data, SessionHandlerInterface $handler): self
    {
        /** @var self $self */
        $self = bean(static::class);

        // Initial properties
        $self->data    = $data;
        $self->handler = $handler;

        return $self;
    }

    public function get($key, $default=null)
    {

    }

    public function getMulti($keys)
    {}

    public function set(string $key, $value): void
    {}

    public function setMulti(array $data): void
    {}

    /**
     * @param $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return true;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {}

    public function remove($key)
    {}

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if ($this->sessionId) {
            $value = $this->handler->read($this->sessionId);

            return JsonHelper::encode($value);
        }

        return '';
    }

    public function clear(): void
    {
    }

    /*************************************************************
     * Session data operate
     ************************************************************/

    /**
     * Flash data name
     *
     * __flash.old
     * __flash.new
     *
     * @var string
     */
    protected $flashName = '__flash';

    /**
     * 设置闪存消息(存储后只能使用一次，即在第一次获取后将被删除)
     * @param  string $name 名称
     * @param  mixed $value 值
     * @return self
     */
    public function setFlash($name,$value)
    {

    }

    /**
     * 获取闪存消息(默认在获取后将被删除)
     * @author inhere
     * @date   2015-09-27
     * @param  string $name 名称
     * @param  mixed $default 默认值
     * @param bool $del 在获取后是否删除它
     * @return mixed|null
     */
    public function getFlash($name, $default=null, $del=true)
    {

    }

    /**
     * @param array $flash
     * @return Session
     */
    public function setFlashs(array $flash)
    {
        return $this->set($this->flashName, $flash);
    }

    /**
     * @return mixed
     */
    public function getFlashs()
    {
        return $this->get($this->flashName);
    }

    /**
     * @return Session
     */
    public function clearFlash()
    {
        return $this->set($this->flashName,[]);
    }
    /*************************************************************
     * Session data operate
     ************************************************************/

    /**
     * Whether a offset exists
     *
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Defined by IteratorAggregate interface
     * Returns an iterator for this object, for use with foreach
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getAll());
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

}
