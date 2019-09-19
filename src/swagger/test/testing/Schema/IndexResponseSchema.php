<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiProperty;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;

/**
 * Class IndexResponseSchema
 *
 * @since 2.0
 *
 * @ApiSchema()
 */
class IndexResponseSchema extends ResponseSchema
{
    /**
     * @ApiSchema()
     *
     * @var IndexData
     */
    protected $data;
}