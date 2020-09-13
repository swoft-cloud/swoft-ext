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
 * Class ApiRequestBody
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiRequestBody
{
    /**
     * @var string
     */
    private $schema = '';

    /**
     * @var string
     */
    private $description = 'Request body';

    /**
     * @var string
     */
    private $contentType = ContentType::JSON;

    /**
     * @var bool
     */
    private $required = false;

    /**
     * ApiRequestBody constructor.
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
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
        if (isset($values['contentType'])) {
            $this->contentType = $values['contentType'];
        }
        if (isset($values['required'])) {
            $this->required = $values['required'];
        }
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
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }
}
