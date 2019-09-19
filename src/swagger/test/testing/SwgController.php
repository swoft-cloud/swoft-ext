<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Validator\Annotation\Mapping\Validate;
use SwoftTest\Swagger\Testing\Schema\IndexRequestSchema;
use SwoftTest\Swagger\Testing\Schema\IndexResponseSchema;
use SwoftTest\Swagger\Testing\Validator\TestValidator;
use SwoftTest\Swagger\Testing\Validator\TestValidator2;

/**
 * Class SwgController
 *
 * @since 2.0
 *
 * @Controller(prefix="swg")
 */
class SwgController
{
    /**
     * Swagger index action
     *
     * @Validate(validator=TestValidator::class, fields={"name", "age"})
     * @Validate(validator=TestValidator2::class, unfields={"content"})
     * @RequestMapping()
     *
     * @ApiOperation()
     * @ApiRequestBody(schema=IndexRequestSchema::class)
     * @ApiResponse(schema=IndexResponseSchema::class)
     *
     * @return string
     */
    public function index(): string
    {
        return 'index';
    }
}