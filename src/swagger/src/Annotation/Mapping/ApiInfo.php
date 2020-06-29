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
 * Class ApiInfo
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiInfo
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $termsOfService = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * ApiInfo constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->title = $values['value'];
        }
        if (isset($values['title'])) {
            $this->title = $values['title'];
        }
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
        if (isset($values['termsOfService'])) {
            $this->termsOfService = $values['termsOfService'];
        }
        if (isset($values['version'])) {
            $this->version = $values['version'];
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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
    public function getTermsOfService(): string
    {
        return $this->termsOfService;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
