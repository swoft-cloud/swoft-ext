<?php declare(strict_types=1);


namespace Swoft\Breaker\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Breaker
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("fallback", type="string"),
 *     @Attribute("sucThreshold", type="int"),
 *     @Attribute("failThreshold", type="int"),
 *     @Attribute("timeout", type="float"),
 *     @Attribute("forceOpen", type="bool"),
 *     @Attribute("forceClose", type="bool"),
 * })
 */
class Breaker
{
    /**
     * @var string
     */
    private $fallback = '';

    /**
     * @var int
     */
    private $sucThreshold = 3;

    /**
     * @var int
     */
    private $failThreshold = 3;

    /**
     * @var float
     */
    private $timeout = 0;

    /**
     * @var bool
     */
    private $forceOpen = false;

    /**
     * @var bool
     */
    private $forceClose = false;

    /**
     * Breaker constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->fallback = $values['value'];
        }

        if (isset($values['fallback'])) {
            $this->fallback = $values['fallback'];
        }

        if (isset($values['sucThreshold'])) {
            $this->sucThreshold = $values['sucThreshold'];
        }

        if (isset($values['failThreshold'])) {
            $this->failThreshold = $values['failThreshold'];
        }

        if (isset($values['timeout'])) {
            $this->timeout = $values['timeout'];
        }

        if (isset($values['forceOpen'])) {
            $this->forceOpen = $values['forceOpen'];
        }

        if (isset($values['forceClose'])) {
            $this->forceClose = $values['forceClose'];
        }
    }

    /**
     * @return string
     */
    public function getFallback(): string
    {
        return $this->fallback;
    }

    /**
     * @return int
     */
    public function getSucThreshold(): int
    {
        return $this->sucThreshold;
    }

    /**
     * @return int
     */
    public function getFailThreshold(): int
    {
        return $this->failThreshold;
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @return bool
     */
    public function isForceOpen(): bool
    {
        return $this->forceOpen;
    }

    /**
     * @return bool
     */
    public function isForceClose(): bool
    {
        return $this->forceClose;
    }
}