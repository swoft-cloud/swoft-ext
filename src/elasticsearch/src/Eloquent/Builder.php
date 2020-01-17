<?php declare(strict_types=1);

namespace Swoft\Elasticsearch\Eloquent;

use Closure;
use Exception;
use Jcsp\Core\Pagination\LengthAwarePaginator;
use Swoft\Elasticsearch\Elasticsearch;
use Swoft\Elasticsearch\Exception\ElasticsearchException;
use Swoft\Elasticsearch\Pool;

/**
 * Class Builder
 *
 * @since   2.0
 *
 * @package Swoft\Elasticsearch\Eloquent
 */
class Builder
{

    /**
     * @var string
     */
    protected $pool = '';

    /**
     * @var string
     */
    protected $index = '';

    /**
     * @var string
     */
    protected $type = '_doc';

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $select = [];

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    private $queryIndices = [0, 0];

    /**
     * @var array
     */
    private $sort = [];

    /**
     * @var array
     */
    private $limit = [
        'from' => 0,
        'size' => 0,
    ];

    /**
     * @var array
     */
    private $sentence = [];

    /**
     * @var array
     */
    protected $operate = ['=', '>', '<', '>=', '<=', '!=', '<>', 'in', 'not in', 'like', 'regex', 'prefix'];

    /**
     * @var string
     */
    protected $createTimeField = 'es_created_time';

    /**
     * @var string
     */
    protected $updateTimeField = 'es_updated_time';

    /**
     * @var array
     */
    private $fields = ['*'];

    /**
     * Builder constructor.
     *
     * @param string $modelName
     */
    public function __construct(string $modelName)
    {
        /** @var Model $model */
        $model                 = new $modelName;
        $this->model           = $modelName;
        $this->index           = $model->getIndex();
        $this->type            = $model->getType();
        $this->createTimeField = $model->getCreateTimeField();
        $this->updateTimeField = $model->getUpdateTimeField();
        $this->pool            = $model->getPool();
        $this->pool            = !empty($this->pool) ? $this->pool : Pool::DEFAULT_POOL;

        unset($model);
    }

    /**
     * select
     *
     * @param array $fields
     *
     * @return Builder
     */
    public function select(array $fields): Builder
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * setPool
     *
     * @param string $pool
     *
     * @return Builder
     */
    public function setPool(string $pool = Pool::DEFAULT_POOL): Builder
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * index
     *
     * @param string $index
     *
     * @return Builder
     */
    public function setIndex(string $index): Builder
    {
        $this->index = $index;

        return $this;
    }

    /**
     * setType
     *
     * @param string $type
     *
     * @return Builder
     */
    public function setType(string $type): Builder
    {
        $this->type = $type;

        return $this;
    }

    /**
     * when
     *
     * @param bool    $bool
     * @param Closure $callback
     *
     * @return $this
     */
    public function when(bool $bool, Closure $callback)
    {
        if ($bool) {
            $callback($this);
        }

        return $this;
    }

    /**
     * where
     *
     * @param $conditions
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function where($conditions): Builder
    {
        if (is_array($conditions)) {
            foreach ($conditions as $key => $value) {
                $query = &$this->query;
                $count = count($this->queryIndices);
                if (is_array($value)) {
                    if (count($value) != 3 || !in_array($value[1], $this->operate)) {
                        throw new ElasticsearchException('elasticsearch builder error: ' . $this->index . ' ' . json_encode($value));
                    }
                    foreach ($this->queryIndices as $queryKey => $queryIndex) {
                        if (!isset($query[$queryIndex])) {
                            if ($queryKey < $count - 1) {
                                $query[$queryIndex] = [];
                            } else {
                                $query[$queryIndex] = $this->parseWhere(...$value);
                            }
                        }
                        $query = &$query[$queryIndex];
                    }
                } else {
                    foreach ($this->queryIndices as $queryKey => $queryIndex) {
                        if (!isset($query[$queryIndex])) {
                            if ($queryKey < $count - 1) {
                                $query[$queryIndex] = [];
                            } else {
                                $query[$queryIndex] = $this->parseWhere($key, '=', $value);
                            }
                        }
                        $query = &$query[$queryIndex];
                    }
                }
                $this->queryIndices[$count - 1]++;
            }
        } else {
            if ($conditions instanceof Closure) {
                $callback           = $conditions;
                $count              = count($this->queryIndices);
                $this->queryIndices = array_pad($this->queryIndices, $count + 2, 0);
                $callback($this);
                $this->queryIndices = array_slice($this->queryIndices, 0, $count, false);
                $this->queryIndices[$count - 1]++;
            }
        }

        return $this;
    }

    /**
     * orWhere
     *
     * @param $conditions
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function orWhere($conditions): Builder
    {
        if (is_array($conditions)) {
            $query = &$this->query;
            $count = count($this->queryIndices);

            $this->queryIndices[$count - 2]++;
            $this->queryIndices[$count - 1] = 0;

            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    if (count($value) != 3 || !in_array($value[1], $this->operate)) {
                        throw new ElasticsearchException('elasticsearch builder error: ' . $this->index . ' ' . json_encode($value));
                    }
                    foreach ($this->queryIndices as $queryKey => $queryIndex) {
                        if (!isset($query[$queryIndex])) {
                            if (($queryKey + 1) < $count) {
                                $query[$queryIndex] = [];
                            } else {
                                $query[$queryIndex] = $this->parseWhere(...$value);
                            }
                        }
                        $query = &$query[$queryIndex];
                    }
                } else {
                    foreach ($this->queryIndices as $queryKey => $queryIndex) {
                        if (!isset($query[$queryIndex])) {
                            if (($queryKey + 1) < $count) {
                                $query[$queryIndex] = [];
                            } else {
                                $query[$queryIndex] = $this->parseWhere($key, '=', $value);
                            }
                        }
                        $query = &$query[$queryIndex];
                    }
                }
                $this->queryIndices[$count - 1]++;
            }
        } else {
            if ($conditions instanceof Closure) {
                $callback = $conditions;
                $count    = count($this->queryIndices);

                $this->queryIndices[$count - 2]++;
                $this->queryIndices[$count - 1] = 0;

                $this->queryIndices = array_pad($this->queryIndices, $count + 2, 0);
                $callback($this);
                $this->queryIndices = array_slice($this->queryIndices, 0, $count, false);
                $this->queryIndices[$count - 1]++;
            }
        }

        return $this;
    }

    /**
     * whereIn
     *
     * @param string $field
     * @param array  $data
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function whereIn(string $field, array $data): Builder
    {
        return $this->where([[$field, 'in', $data]]);
    }

    /**
     * whereNotIn
     *
     * @param string $field
     * @param array  $data
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function whereNotIn(string $field, array $data): Builder
    {
        return $this->where([[$field, 'not in', $data]]);
    }

    /**
     * whereLike
     *
     * @param string $field
     * @param string $value
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function whereLike(string $field, string $value): Builder
    {
        return $this->where([[$field, 'like', $value]]);
    }

    /**
     * whereLike
     *
     * @param string $field
     * @param string $value
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function wherePrefix(string $field, string $value): Builder
    {
        return $this->where([[$field, 'prefix', $value]]);
    }

    /**
     * whereLike
     *
     * @param string $field
     * @param string $value
     *
     * @return Builder
     * @throws ElasticsearchException
     */
    public function whereRegex(string $field, string $value): Builder
    {
        return $this->where([[$field, 'regex', $value]]);
    }

    /**
     * get
     *
     * @return \Swoft\Elasticsearch\Eloquent\Collection
     * @throws ElasticsearchException
     */
    public function get(): Collection
    {
        $this->limit['size'] = $this->limit['size'] > 0 ? $this->limit['size'] : $this->count();

        $data = Elasticsearch::connection($this->pool)->search([
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => [
                '_source' => $this->fields,
                'query'   => $this->parseJson($this->query),
                'sort'    => $this->sort,
            ],
            'from'  => $this->limit['from'],
            'size'  => $this->limit['size'],
        ]);

        $result = [];
        $hits   = $data['hits']['hits'] ?? [];
        foreach ($hits as $hit) {
            /** @var Model $model */
            $model = new $this->model;
            $model->setIndex($hit['_index']);
            $model->setType($hit['_type']);
            $model->setId($hit['_id']);
            $model->setSource($hit['_source']);
            array_push($result, $model);
        }

        return Collection::make($result);
    }

    /**
     * first
     *
     * @return Model|null
     * @throws ElasticsearchException
     */
    public function first(): ?Model
    {
        $data = Elasticsearch::connection($this->pool)->search([
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => [
                '_source' => $this->fields,
                'query'   => $this->parseJson($this->query),
                'sort'    => $this->sort,
            ],
            'from'  => 0,
            'size'  => 1,
        ]);
        $hit  = $data['hits']['hits'][0] ?? [];
        if (empty($hit)) {
            return null;
        }

        /** @var Model $model */
        $model = new $this->model;
        $model->setIndex($hit['_index']);
        $model->setType($hit['_type']);
        $model->setId($hit['_id']);
        $model->setSource($hit['_source']);

        return $model;
    }

    /**
     * paginate
     *
     * @param int $page
     * @param int $size
     *
     * @return LengthAwarePaginator
     * @throws ElasticsearchException
     */
    public function paginate(int $page = 1, int $size = 10): LengthAwarePaginator
    {
        $page  = $page < 1 ? 1 : intval($page);
        $size  = $size < 1 ? 1 : intval($size);
        $from  = ($page - 1) * $size;
        $data  = Elasticsearch::connection($this->pool)->search([
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => [
                '_source' => $this->fields,
                'query'   => $this->parseJson($this->query),
                'sort'    => $this->sort,
            ],
            'from'  => $from,
            'size'  => $size,
        ]);
        $total = $data['hits']['total'] ?? 0;
        $hits  = $data['hits']['hits'] ?? [];
        $list  = [];
        foreach ($hits as $hit) {
            /** @var Model $model */
            $model = new $this->model;
            $model->setIndex($hit['_index']);
            $model->setType($hit['_type']);
            $model->setId($hit['_id']);
            $model->setSource($hit['_source']);
            array_push($list, $model);
        }

        return LengthAwarePaginator::create($list, $total, $size, $page);
    }

    /**
     * count
     *
     * @return int
     */
    public function count(): int
    {
        try {
            $data  = Elasticsearch::connection($this->pool)->count([
                'index' => $this->index,
                'type'  => $this->type,
                'body'  => [
                    'query' => $this->parseJson($this->query),
                ],
            ]);
            $count = $data['count'] ?? 0;
        } catch (Exception $exception) {
            $count = 0;
        }

        return $count;
    }

    /**
     * orderBy
     *
     * @param string $field
     * @param string $sort
     *
     * @return Builder
     */
    public function orderBy(string $field, string $sort = 'asc'): Builder
    {
        $sort = in_array(strtolower($sort), ['asc', 'desc']) ? strtolower($sort) : 'asc';
        array_push($this->sort, [$field => ['order' => $sort]]);

        return $this;
    }

    /**
     * limit
     *
     * @param int $from
     * @param int $size
     *
     * @return Builder
     */
    public function limit(int $from = 0, int $size = 10): Builder
    {
        $this->limit['from'] = $from > 0 ? $from : 0;
        $this->limit['size'] = $size > 0 ? $size : 10;

        return $this;
    }

    /**
     * create
     *
     * @param array $value
     *
     * @return Model
     * @throws ElasticsearchException
     */
    public function create(array $value): Model
    {
        $value[$this->createTimeField] = $value[$this->updateTimeField] = date('Y-m-d H:i:s');

        $data = Elasticsearch::connection($this->pool)->index([
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => $value,
        ]);

        if (!isset($data['result']) || $data['result'] != 'created') {
            throw new ElasticsearchException('elasticsearch builder error: create failed.');
        }

        /** @var Model $model */
        $model = new $this->model;
        $model->setIndex($data['_index']);
        $model->setType($data['_type']);
        $model->setId($data['_id']);
        $model->setVersion($data['_version']);
        $model->setSource($value);

        return $model;
    }

    /**
     * insert
     *
     * @param array $values
     *
     * @return Collection
     * @throws ElasticsearchException
     */
    public function insert(array $values): Collection
    {
        $body = [];
        foreach ($values as $value) {
            $value[$this->createTimeField] = $value[$this->updateTimeField] = date('Y-m-d H:i:s');
            array_push($body, ['index' => ['_index' => $this->index, '_type' => $this->type]]);
            array_push($body, $value);
        }

        $data = Elasticsearch::connection($this->pool)->bulk(['body' => $body]);
        if (!isset($data['errors']) || $data['errors'] !== false) {
            throw new ElasticsearchException('elasticsearch builder error: insert failed.');
        }

        $models = [];
        foreach ($data['items'] as $key => $datum) {
            /** @var Model $model */
            $model = new $this->model;
            $model->setIndex($datum['index']['_index']);
            $model->setType($datum['index']['_type']);
            $model->setId($datum['index']['_id']);
            $model->setVersion($datum['index']['_version']);
            $model->setSource($values[$key]);
            array_push($models, $model);
        }

        return Collection::make($models);
    }

    /**
     * update
     *
     * @param string $id
     * @param array  $value
     *
     * @return bool
     */
    public function update(string $id, array $value): bool
    {
        try {
            $value[$this->updateTimeField] = date('Y-m-d H:i:s');

            $data = Elasticsearch::connection($this->pool)->update([
                'index' => $this->index,
                'type'  => $this->type,
                'id'    => $id,
                'body'  => [
                    'doc' => $value,
                ],
            ]);

            if (isset($data['result']) && ($data['result'] == 'updated' || $data['result'] == 'noop')) {
                return true;
            }

            return false;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * delete
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        try {
            $data = Elasticsearch::connection($this->pool)->delete([
                'index' => $this->index,
                'type'  => $this->type,
                'id'    => $id,
            ]);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * toSql
     *
     * @return array
     */
    public function toSql(): array
    {
        return $this->parseJson($this->query);
    }

    /**
     * parseWhere
     *
     * @param string $field
     * @param string $operate
     * @param        $value
     *
     * @return array
     */
    protected function parseWhere(string $field, string $operate, $value): array
    {
        switch ($operate) {
            case '=':
                $type   = 'must';
                $result = ['match' => [$field => $value]];
                break;
            case '>':
                $type   = 'must';
                $result = ['range' => [$field => ['gt' => $value]]];
                break;
            case '<':
                $type   = 'must';
                $result = ['range' => [$field => ['lt' => $value]]];
                break;
            case '>=':
                $type   = 'must';
                $result = ['range' => [$field => ['gte' => $value]]];
                break;
            case '<=':
                $type   = 'must';
                $result = ['range' => [$field => ['lte' => $value]]];
                break;
            case '<>':
            case '!=':
                $type   = 'must_not';
                $result = ['match' => [$field => $value]];
                break;
            case 'in':
                $type   = 'must';
                $result = ['terms' => [$field => $value]];
                break;
            case 'not in':
                $type   = 'must_not';
                $result = ['terms' => [$field => $value]];
                break;
            case 'like':
                $type   = 'must';
                $result = ['wildcard' => [$field => $value]];
                break;
            case 'regex':
                $type   = 'must';
                $result = ['regexp' => [$field => $value]];
                break;
            case 'prefix':
                $type   = 'must';
                $result = ['prefix' => [$field => $value]];
                break;
        }

        $sentence = json_encode($result);

        $this->sentence[$sentence]['sentence'] = $result;
        $this->sentence[$sentence]['type']     = $type;

        return $result;
    }

    /**
     * parseJson
     *
     * @param $data
     *
     * @return array
     */
    protected function parseJson($data): array
    {
        if (empty($data)) {
            return ['match_all' => (object)[]];
        }

        $jsonData = [];
        $isShould = $this->isShould($data);
        $type     = $isShould ? 'should' : 'must';

        $jsonData['bool'][$type] = [];

        if ($isShould) {
            foreach ($data as $datum) {
                $temp = [];
                foreach ($datum as $item) {
                    if ($this->isSentence($item)) {
                        $sentence = json_encode($item);

                        $temp['bool'][$this->sentence[$sentence]['type']][] = $item;
                    } else {
                        $temp['bool']['must'][] = $this->parseJson($item);
                    }
                }
                $jsonData['bool'][$type][] = $temp;
            }
        }

        return $jsonData;
    }

    /**
     * isShould
     *
     * @param $data
     *
     * @return bool
     */
    private function isShould($data): bool
    {
        $result = !$this->isSentence($data) ? true : false;
        if (!$this->isSentence($data)) {
            foreach ($data as $datum) {
                if ($this->isSentence($datum)) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * isSentence
     *
     * @param $data
     *
     * @return bool
     */
    private function isSentence($data): bool
    {
        $sentence = json_encode($data);

        return isset($this->sentence[$sentence]);
    }

}
