<?php declare(strict_types=1);


namespace Swoft\Swagger\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiServer
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class ApiServer
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * ApiServer constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->url = $values['value'];
        }
        if (isset($values['url'])) {
            $this->url = $values['url'];
        }
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
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
    public function getDescription(): string
    {
        return $this->description;
    }
}