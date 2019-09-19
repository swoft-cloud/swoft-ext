<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing\Validator;


use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TestValidator2
 *
 * @since 2.0
 *
 * @Validator()
 */
class TestValidator2
{
    /**
     * @IsInt()
     *
     * @var int
     */
    protected $id;

    /**
     * @IsString()
     *
     * @var string
     */
    protected $title = '';

    /**
     * @IsString()
     *
     * @var string
     */
    protected $content = '';
}