<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     * @var Property[]
     */
    protected $properties = [];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

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
