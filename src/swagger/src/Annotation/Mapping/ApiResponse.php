<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiResponse
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiResponse
{
    /**
     * @var string
     */
    private $status = '200';

    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @var string
     */
    private $charset = 'UTF-8';

    /**
     * @var string
     */
    private $schema = '';

    /**
     * ApiResponse constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->schema = $values['value'];
        }
        if (isset($values['schema'])) {
            $this->schema = $values['schema'];
        }
        if (isset($values['contentType'])) {
            $this->contentType = $values['contentType'];
        }
        if (isset($values['status'])) {
            $this->status = $values['status'];
        }
        if (isset($values['charset'])) {
            $this->charset = $values['charset'];
        }
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }
}