<?php declare(strict_types=1);

/**
 * length page
 */

namespace Swoft\Elasticsearch\Eloquent;

use Swoft\Stdlib\Collection;

/**
 * 分页列表
 */
class Pagination
{
    /**
     * 每页数
     *
     * @var [type]
     */
    private $perPage;
    /**
     * 总页数
     *
     * @var [type]
     */
    private $countPage;
    /**
     * 总数
     *
     * @var [type]
     */
    private $total;
    /**
     * 当前页
     *
     * @var [type]
     */
    private $currentPage;
    /**
     * 数据
     *
     * @var [type]
     */
    private $items;

    public function __construct($items, int $total, int $perPage, $currentPage = null)
    {
        $this->total       = $total;
        $this->perPage     = $perPage;
        $this->countPage   = max((int)ceil($total / $perPage), 1);
        $this->currentPage = $currentPage ? : 1;
        $this->items       = $items instanceof Collection ? $items : Collection::make($items);
    }

    /**
     * @return int
     */
    public function getCurPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * 数据
     *
     * @return array
     */
    public function getTtems()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getCountPage(): int
    {
        return $this->countPage;
    }

    public function getLastPage(): int
    {
        return $this->countPage;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'cur_page'   => $this->getCurPage(),
            'list'       => $this->getTtems(),
            'page_count' => $this->getCountPage(),
            'page_size'  => $this->getPerPage(),
            'total'      => $this->getTotal(),
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * create
     *
     * @param      $items
     * @param int  $total
     * @param int  $perPage
     * @param null $currentPage
     *
     * @return Pagination
     */
    public static function create($items, int $total, int $perPage, $currentPage = null): self
    {
        return new self($items, $total, $perPage, $currentPage);
    }
}
