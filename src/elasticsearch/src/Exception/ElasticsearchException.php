<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Elasticsearch\Exception;

use Exception;

/**
 * Class ElasticsearchException
 *
 * @since   2.8
 *
 * @package Swoft\Elasticsearch\Exception
 */
class ElasticsearchException extends Exception
{
    /**
     * @var array
     */
    private $response = [];

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     */
    public function setResponse(array $response): void
    {
        $this->response = $response;
    }
}
