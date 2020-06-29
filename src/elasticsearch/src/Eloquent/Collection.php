<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Elasticsearch\Eloquent;

use Swoft\Stdlib\Collection as SwoftCollection;

/**
 * Class Collection
 *
 * @package Swoft\Elasticsearch\Eloquent
 */
class Collection extends SwoftCollection
{
    /**
     * update
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data): bool
    {
        $bool = true;
        foreach ($this->items as $item) {
            $bool = $item->update($data) && $bool;
        }

        return $bool;
    }

    /**
     * delete
     *
     * @return bool
     */
    public function delete(): bool
    {
        $bool = true;
        foreach ($this->items as $item) {
            $bool = $bool && $item->delete();
        }

        return $bool;
    }
}
