<?php declare(strict_types=1);


namespace Swoft\Swagger\Dto;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Swagger\Contract\DtoInterface;

/**
 * Class JsonDto
 *
 * @since 2.0
 *
 * @Bean()
 */
class JsonDto implements DtoInterface
{
    /**
     * @param object $object
     *
     * @return string
     */
    public function encode($object): string
    {
        return '';
    }
}