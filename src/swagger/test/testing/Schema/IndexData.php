<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiProperty;
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
     * Age params
     *
     * @ApiProperty()
     *
     * @var int
     */
    public $page = 1;

    /**
     * Count params
     *
     * @ApiProperty()
     *
     * @var int
     */
    public $count = 0;

    /**
     * User Info
     *
     * @ApiPropertyEntity(name="userInfo", fields={"name"})
     *
     * @var User
     */
    public $user;

    /**
     * User list
     *
     * @ApiPropertyEntity(entity=User::class, unfields={"age"})
     *
     * @var User[]
     */
    public $list = [];

    /**
     * Other data
     *
     * @ApiPropertySchema()
     *
     * @var IndexOther
     */
    public $other;

    /**
     * @var int
     */
    public $ext = 1;
}