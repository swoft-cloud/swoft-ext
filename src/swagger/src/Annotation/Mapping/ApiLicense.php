<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiLicense
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("CLASS")
 */
class ApiLicense
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
     * ApiLicense constructor.
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
}