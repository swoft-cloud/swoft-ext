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

/**
 * Class ApiPropertySchema
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class ApiPropertySchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $schema = '';

    /**
     * @var string
     */
    private $type = 'object';

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $unfields = [];

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var bool
     */
    private $required = true;

    /**
     * ApiPropertySchema constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['schema'])) {
            $this->schema = $values['schema'];
        }
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['fields'])) {
            $this->fields = $values['fields'];
        }
        if (isset($values['unfields'])) {
            $this->unfields = $values['unfields'];
        }
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
        if (isset($values['required'])) {
            $this->required = $values['required'];
        }
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getUnfields(): array
    {
        return $this->unfields;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
}
