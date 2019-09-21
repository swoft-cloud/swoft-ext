<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

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
     * @var int
     */
    public $id;

    /**
     * @var integer
     */
    public $count;

    /**
     * @var string
     */
    public $desc = '';

    /**
     * @var double
     */
    public $amount;

    /**
     * @var bool
     */
    public $isComplete = false;

    /**
     * @var boolean
     */
    public $isBegin = false;
}