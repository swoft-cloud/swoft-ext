<?php declare(strict_types=1);

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
