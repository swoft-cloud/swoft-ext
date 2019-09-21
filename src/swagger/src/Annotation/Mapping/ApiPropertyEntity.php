<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiEntity
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class ApiPropertyEntity
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $unfields = [];

    /**
     * ApiEntity constructor.
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
}