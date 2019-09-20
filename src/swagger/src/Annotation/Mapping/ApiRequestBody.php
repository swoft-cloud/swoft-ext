<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

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
    private $contentType = 'application/json';

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