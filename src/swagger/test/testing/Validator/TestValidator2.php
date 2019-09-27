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
     * Id params
     *
     * @IsInt()
     *
     * @var int
     */
    protected $id = 9;

    /**
     * Title
     *
     * @IsString()
     *
     * @var string
     */
    protected $title = '';

    /**
     * Content params
     *
     * @IsString()
     *
     * @var string
     */
    protected $content = '';
}