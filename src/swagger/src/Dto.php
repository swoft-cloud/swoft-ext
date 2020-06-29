<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Swagger;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Swagger\Contract\DtoInterface;
use Swoft\Swagger\Dto\JsonDto;
use Swoft\Swagger\Exception\DtoException;

/**
 * Class Dto
 *
 * @since 2.0
 *
 * @Bean()
 */
class Dto implements DtoInterface
{
    /**
     * Json
     */
    public const JSON = 'json';

    /**
     * @var string
     */
    private $type = self::JSON;

    /**
     * @var array
     */
    private $dtos = [];

    /**
     * @param object $object
     *
     * @return string
     * @throws DtoException
     */
    public function encode($object): string
    {
        $dtos = array_merge($this->dtos, $this->defaultDto());
        if (!isset($dtos[$this->type])) {
            throw new DtoException(
                sprintf('%s dto is not supported!', $this->type)
            );
        }

        $dto = $dtos[$this->type];
        if (!($dto instanceof DtoInterface)) {
            throw new DtoException('Dto is not instanceof DtoInterface!');
        }

        return $dto->encode($object);
    }

    /**
     * @return array
     */
    private function defaultDto(): array
    {
        return [
            self::JSON => bean(JsonDto::class)
        ];
    }
}
