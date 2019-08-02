<?php declare(strict_types=1);

namespace Swoft\Crontab\Annotaion\Mapping;

/**
 * Class Cron
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("value", type="string")
 * })
 */
class Cron
{
    /**
     * @var string
     */
    private $cron = '';

    /**
     * Validator constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->cron = $values['value'];
        }
    }

    /**
     * @return string
     */
    public function getCron(): string
    {
        return $this->cron;
    }
}
