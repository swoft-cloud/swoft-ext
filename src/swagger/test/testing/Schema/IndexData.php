<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiEntity;
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
     * @ApiEntity(fields={"name"})
     *
     * @var User
     */
    public $user;

    /**
     * @ApiEntity(unfields={"age"})
     *
     * @var User[]
     */
    public $list = [];

    /**
     * @ApiSchema()
     *
     * @var IndexOther
     */
    public $other;
}