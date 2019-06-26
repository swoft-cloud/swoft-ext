<?php declare(strict_types=1);


namespace Swoft\Consul;


use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class Response
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response
{
    use PrototypeTrait;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $status;

    /**
     * @param array  $headers
     * @param string $body
     * @param int    $status
     *
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(array $headers, string $body, int $status = 200): void
    {
        $self = self::__instance();

        $self->body    = $body;
        $self->status  = $status;
        $self->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function json(): array
    {
        return JsonHelper::decode($this->body, true);
    }
}