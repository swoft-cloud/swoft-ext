<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiSchema
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class ApiSchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * ApiSchema constructor.
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
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}