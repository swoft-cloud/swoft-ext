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
 * Class ApiOperation
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 */
class ApiOperation
{
    /**
     * @var string[]
     */
    private $tags = [];

    /**
     * @var string
     */
    private $summary = '';

    /**
     * Default is description of action function
     *
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $operationId = '';

    /**
     * ApiOperation constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->description = $values['value'];
        }
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
        if (isset($values['tags'])) {
            $this->tags = $values['tags'];
        }
        if (isset($values['summary'])) {
            $this->summary = $values['summary'];
        }
        if (isset($values['operationId'])) {
            $this->operationId = $values['operationId'];
        }
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
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
    public function getOperationId(): string
    {
        return $this->operationId;
    }
}
