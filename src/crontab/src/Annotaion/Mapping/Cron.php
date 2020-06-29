<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
    private $value = '';

    /**
     * Validator constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->value = $values['value'];
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
