<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Swagger\Annotation\Mapping\ApiServer;
use Swoft\Validator\Annotation\Mapping\Validate;
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
     * @ApiOperation(tags={"user"}, summary="api index", description="home index")
     * @ApiRequestBody()
     * @ApiResponse(schema=IndexResponseSchema::class)
     * @ApiServer(
     *     url="https://index.swoft.org",
     *     description="v1"
     * )
     * @ApiServer(
     *     url="https://index2.swoft.org",
     *     description="v2"
     * )
     *
     * @return string
     */
    public function index(): string
    {
        return 'index';
    }
}