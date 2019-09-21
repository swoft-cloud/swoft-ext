<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiPropertyEntity;
use Swoft\Swagger\Annotation\Mapping\ApiPropertySchema;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;
use Swoft\Swagger\Schema;
use SwoftTest\Swagger\Testing\Entity\User;

/**
 * Class IndexData
 *
 * @since 2.0
 *
 * @ApiSchema()
 */
class IndexData extends Schema
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $count = 0;

    /**
     * @ApiPropertyEntity(fields={"name"})
     *
     * @var User
     */
    public $user;

    /**
     * @ApiPropertyEntity(unfields={"age"})
     *
     * @var User[]
     */
    public $list = [];

    /**
     * @ApiPropertySchema()
     *
     * @var IndexOther
     */
    public $other;
}