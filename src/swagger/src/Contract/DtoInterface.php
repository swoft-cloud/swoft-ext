<?php declare(strict_types=1);


namespace Swoft\Swagger\Contract;


/**
 * Class DtoInterface
 *
 * @since 2.0
 */
interface DtoInterface
{
    /**
     * @param object $object
     *
     * @return string
     */
    public function encode($object): string;
}