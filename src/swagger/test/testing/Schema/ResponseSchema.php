<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Schema;

/**
 * Class ResponseSchema
 *
 * @since 2.0
 */
abstract class ResponseSchema extends Schema
{
    /**
     * @var int
     */
    public $status = 200;

    /**
     * @var int
     */
    public $code = 0;

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var int
     */
    public $serverTime = 0;

    /**
     * @var object
     */
    public $data;
}