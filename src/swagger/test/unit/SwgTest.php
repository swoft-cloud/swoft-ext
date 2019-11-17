<?php declare(strict_types=1);


namespace SwoftTest\Swagger\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Bean\BF;
use Swoft\Swagger\Dto;
use Swoft\Swagger\Swagger;
use SwoftTest\Swagger\Testing\Entity\User;
use SwoftTest\Swagger\Testing\Schema\IndexData;
use SwoftTest\Swagger\Testing\Schema\IndexOther;
use SwoftTest\Swagger\Testing\Schema\IndexRequestSchema;
use SwoftTest\Swagger\Testing\Schema\IndexResponseSchema;

class SwgTest extends TestCase
{
    public function testGen(): void
    {
        /* @var Swagger $swagger */
        $swagger = BF::getBean(Swagger::class);
        $swagger->gen();
    }

    public function testDto(): void
    {

        $user = new User();
        $user->setId(12);
        $user->setName('name');
        $user->setPwd('PWD');
        $user->setAge(12);
        $user->setUserDesc('use desc');

        $other = IndexOther::new([
            'id'    => 12,
            'count' => 100,
            'desc'  => 'desc'
        ]);

        $data = IndexData::new([
            'user'  => $user,
            'list'  => [
                $user
            ],
            'other' => $other
        ]);

        $response = IndexResponseSchema::new([
            'data' => $data
        ]);

        /* @var Dto $dto*/
        $dto = bean(Dto::class);
        $dto->encode($response);
    }
}