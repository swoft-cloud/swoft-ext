<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BF;
use Swoft\Swagger\Swagger;

class SwgTest extends TestCase
{
    public function testGen():void {
        /* @var Swagger $swagger*/
        $swagger = BF::getBean(Swagger::class);
        $swagger->gen();
    }
}