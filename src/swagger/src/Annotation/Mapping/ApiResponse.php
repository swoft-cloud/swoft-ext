<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Swagger\ContentType;

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
    private $contentType = ContentType::JSON;

    /**
     * @var string
     */
    private $charset = 'utf-8';

    /**
     * @var string
     */
    private $schema = '';

    /**
     * @var string
     */
    private $description = '';

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
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
    }

    /**
     * @param string $schema
     */
    public function setSchema(string $schema): void
    {
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
