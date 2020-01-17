<?php declare(strict_types=1);

namespace Swoft\Elasticsearch\Eloquent;

use Swoft\Elasticsearch\Exception\ElasticsearchException;
use Swoft\Stdlib\Contract\Arrayable;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\StringHelper;

/**
 * Class Model
 *
 * @since   2.0
 *
 * @package Swoft\Elasticsearch\Eloquent
 */
class Model implements Arrayable
{

    /**
     * @var string
     */
    protected $pool = 'elasticsearch.pool';

    /**
     * @var string
     */
    protected $_index = '';

    /**
     * @var string
     */
    protected $_type = '_doc';

    /**
     * @var string
     */
    protected $_id = '';

    /**
     * @var int
     */
    protected $_version = 1;

    /**
     * @var array
     */
    protected $_source = [];

    /**
     * @var string
     */
    protected $_createTimeField = 'es_created_time';

    /**
     * @var string
     */
    protected $_updateTimeField = 'es_updated_time';

    public function __construct(array $data = [])
    {
        $index = self::index();

        $this->_index  = $index;
        $this->_source = $data;
    }

    /**
     * @return string
     */
    public function getPool(): string
    {
        return $this->pool;
    }

    /**
     * @param string $pool
     */
    public function setPool(string $pool): void
    {
        $this->pool = $pool;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->_index;
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index): void
    {
        $this->_index = $index;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->_type = $type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->_id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->_id = $id;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->_version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->_version = $version;
    }

    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->_source;
    }

    /**
     * @param array $source
     */
    public function setSource(array $source): void
    {
        $this->_source = $source;
    }

    /**
     * @return string
     */
    public function getCreateTimeField(): string
    {
        return $this->_createTimeField;
    }

    /**
     * @param string $createTimeField
     */
    public function setCreateTimeField(string $createTimeField): void
    {
        $this->_createTimeField = $createTimeField;
    }

    /**
     * @return string
     */
    public function getUpdateTimeField(): string
    {
        return $this->_updateTimeField;
    }

    /**
     * @param string $updateTimeField
     */
    public function setUpdateTimeField(string $updateTimeField): void
    {
        $this->_updateTimeField = $updateTimeField;
    }

    /**
     * update
     *
     * @param array $data
     *
     * @return bool
     * @throws ElasticsearchException
     */
    public function update(array $data): bool
    {
        $result        = self::query()->update($this->_id, $data);
        $this->_source = Arr::merge($this->_source, $data);

        return $result;
    }

    /**
     * delete
     *
     * @return bool
     * @throws ElasticsearchException
     */
    public function delete(): bool
    {
        return self::query()->delete($this->_id);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->_source;
    }

    /**
     * __get
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->_source[$name] ?? null;
    }

    /**
     * query
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public static function query(): Builder
    {
        return new Builder(static::class);
    }

    /**
     * index
     *
     * @return string
     * @throws ElasticsearchException
     */
    public static function index(): string
    {
        $className = static::class;
        $shortName = explode('\\', $className);
        $class     = StringHelper::snake(array_pop($shortName));
        if (!$class) {
            throw new ElasticsearchException('elasticsearch model error: class name is empty.');
        }

        return $class;
    }

    /**
     * create
     *
     * @param array $value
     *
     * @return Model
     * @throws ElasticsearchException
     */
    public static function create(array $value): Model
    {
        return self::query()->create($value);
    }

}
