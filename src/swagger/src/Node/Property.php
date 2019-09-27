<?php declare(strict_types=1);


namespace Swoft\Swagger\Node;


class Property extends Node
{
    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $ref;

    /**
     * @var int
     */
    protected $minimum;

    /**
     * @var int
     */
    protected $maximum;

    /**
     * @var int
     */
    protected $maxLength;

    /**
     * @var int
     */
    protected $minLength;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var array
     */
    protected $enum = [];

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $ref
     */
    public function setRef(string $ref): void
    {
        $this->ref = $ref;
    }

    /**
     * @param int $minimum
     */
    public function setMinimum(int $minimum): void
    {
        $this->minimum = $minimum;
    }

    /**
     * @param int $maximum
     */
    public function setMaximum(int $maximum): void
    {
        $this->maximum = $maximum;
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength(int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @param int $minLength
     */
    public function setMinLength(int $minLength): void
    {
        $this->minLength = $minLength;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default): void
    {
        $this->default = $default;
    }

    /**
     * @param array $enum
     */
    public function setEnum(array $enum): void
    {
        $this->enum = $enum;
    }
}