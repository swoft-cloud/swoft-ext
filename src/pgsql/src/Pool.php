<?php declare(strict_types=1);


namespace Swoft\Pgsql;

use ReflectionException;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Swoft\Pgsql\Connection\Connection;
use Swoft\Pgsql\Exception\PgsqlException;
use Throwable;

/**
 * Class Pool
 *
 * @since 2.0
 *
 */
class Pool extends AbstractPool
{
    /**
     * Default pool name
     */
    const DEFAULT_POOL = 'pgsql.pool';

    /**
     * Database
     *
     * @var PgsqlDb
     */
    protected $pgsqlDb;

    /**
     * Create connection
     *
     * @return ConnectionInterface
     * @throws ContainerException
     * @throws PgsqlException
     * @throws ReflectionException
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->pgsqlDb->createConnection($this);
    }

    /**
     * @return PgsqlDb
     */
    public function getDatabase(): PgsqlDb
    {
        return $this->pgsqlDb;
    }

    /**
     * @return ConnectionInterface
     * @throws ConnectionPoolException
     */
    public function getConnection(): ConnectionInterface
    {
        $connection = parent::getConnection();

        return $connection;
    }
}
