<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiProperty
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class ApiProperty
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var bool
     */
    private $required = true;

    /**
     * ApiProperty constructor.
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
        if (isset($values['required'])) {
            $this->required = $values['required'];
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
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}