<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Schema extends Node
{
    /**
     * @var string
     */
    protected $type = 'object';

    /**
     * @var array
     */
    protected $required = [];

    /**
     * @var Property[]
     */
    protected $properties = [];

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param array $required
     */
    public function setRequired(array $required): void
    {
        $this->required = $required;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function getRequired(): array
    {
        return $this->required;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}