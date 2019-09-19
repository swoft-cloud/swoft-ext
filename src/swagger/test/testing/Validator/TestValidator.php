<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Validator;

use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Max;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TestValidator
 *
 * @since 2.0
 *
 * @Validator()
 */
class TestValidator
{
    /**
     * @IsString()
     *
     * @var string
     */
    protected $name = '';

    /**
     * @IsInt()
     * @Max(100)
     *
     * @var int
     */
    protected $age;

    /**
     * @IsString()
     *
     * @var string
     */
    protected $desc;
}