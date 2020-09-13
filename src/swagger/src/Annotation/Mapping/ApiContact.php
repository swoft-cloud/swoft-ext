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
 * Class ApiContact
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiContact
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * ApiContact constructor.
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
        if (isset($values['url'])) {
            $this->url = $values['url'];
        }
        if (isset($values['email'])) {
            $this->email = $values['email'];
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
