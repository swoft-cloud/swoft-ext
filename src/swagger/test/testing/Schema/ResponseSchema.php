<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Schema;

use Swoft\Swagger\Annotation\Mapping\ApiProperty;
use Swoft\Swagger\Schema;

/**
 * Class ResponseSchema
 *
 * @since 2.0
 */
abstract class ResponseSchema extends Schema
{
    /**
     * Response status
     *
     * @ApiProperty()
     *
     * @var int
     */
    public $status = 200;

    /**
     * Response code
     *
     * @ApiProperty()
     *
     * @var int
     */
    public $code = 0;

    /**
     * Message
     *
     * @ApiProperty()
     *
     * @var string
     */
    public $message = '';

    /**
     * Server time
     *
     * @ApiProperty()
     *
     * @var float
     */
    public $serverTime = 0;

    /**
     * Response data
     *
     * @ApiProperty()
     *
     * @var object
     */
    public $data;
}