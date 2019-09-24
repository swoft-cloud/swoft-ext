<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiProperty;
use Swoft\Swagger\Annotation\Mapping\ApiSchema;
use Swoft\Swagger\Schema;

/**
 * Class IndexOther
 *
 * @since 2.0
 *
 * @ApiSchema()
 */
class IndexOther extends Schema
{
    /**
     * ID params
     *
     * @ApiProperty()
     *
     * @var int
     */
    public $id;

    /**
     * Count params
     *
     * @ApiProperty()
     *
     * @var integer
     */
    public $count;

    /**
     * Desc params
     *
     * @ApiProperty()
     *
     * @var string
     */
    public $desc = '';

    /**
     * Double params
     *
     * @ApiProperty()
     *
     * @var double
     */
    public $amount = 0;

    /**
     * @var bool
     */
    public $isComplete = false;

    /**
     * Bool params
     *
     * @ApiProperty()
     *
     * @var boolean
     */
    public $isBegin = false;
}