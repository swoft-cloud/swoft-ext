<?php declare(strict_types=1);


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
     * ApiPropertySchema constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
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
}