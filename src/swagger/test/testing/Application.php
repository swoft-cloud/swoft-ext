<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Testing;

use Swoft\Swagger\Annotation\Mapping\ApiContact;
use Swoft\Swagger\Annotation\Mapping\ApiInfo;
use Swoft\Swagger\Annotation\Mapping\ApiLicense;

/**
 * Class Application
 *
 * @since 2.0
 *
 * @ApiInfo(
 *     title="Swagger",
 *     description="Swagger test api",
 *     version="1.0"
 * )
 * @ApiContact(
 *     name="swoft",
 *     url="https:://www.swoft.org",
 *     email="swoft@qq.com"
 * )
 * @ApiLicense(
 *     name="Apache 2.0",
 *     url="https://github.com/swoft-cloud/swoft/blob/master/LICENSE"
 * )
 */
class Application
{

}