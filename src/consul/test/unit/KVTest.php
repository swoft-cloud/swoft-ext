<?php declare(strict_types=1);


namespace SwoftTest\Consul\Unit;


use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Consul\KV;
use Swoft\Consul\Response;

class KVTest extends TestCase
{
    /**
     * @var KV
     */
    private $kv;

    /**
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ClientException
     * @throws ServerException
     */
    protected function setUp()
    {
        $this->kv = BeanFactory::getBean(KV::class);
//        $this->kv->delete('test', array('recurse' => true));
    }

    protected function tearDown()
    {
        $this->kv = null;
    }

    public function testSetGetWithDefaultOptions()
    {
        $value = date('r');
        $this->kv->put('/test/my/key', $value);

        $response = $this->kv->get('/test/my/key');
        $this->assertInstanceOf(Response::class, $response);

        $json = $response->json();
        $this->assertSame($value, base64_decode($json[0]['Value']));
    }

    public function testSetGetWithRawOption()
    {
        $value = date('r');
        $this->kv->put('test/my/key', $value);

        $response = $this->kv->get('test/my/key', array('raw' => true));
        $this->assertInstanceOf(Response::class, $response);

        $body = (string)$response->getBody();
        $this->assertSame($value, $body);
    }

    public function testSetGetWithFlagsOption()
    {
        $flags = mt_rand();
        $this->kv->put('test/my/key', 'hello', array('flags' => $flags));

        $response = $this->kv->get('test/my/key');
        $this->assertInstanceOf(Response::class, $response);

        $json = $response->json();
        $this->assertSame($flags, $json[0]['Flags']);
    }

    public function testSetGetWithKeysOption()
    {
        $this->kv->put('test/my/key1', 'hello 1');
        $this->kv->put('test/my/key2', 'hello 2');
        $this->kv->put('test/my/key3', 'hello 3');

        $response = $this->kv->get('test/my', array('keys' => true));
        $this->assertInstanceOf(Response::class, $response);

        $json = $response->json();
        $this->assertSame(array('test/my/key1', 'test/my/key2', 'test/my/key3'), $json);
    }

    public function testDeleteWithDefaultOptions()
    {
        $this->kv->put('test/my/key', 'hello');
        $this->kv->get('test/my/key');
        $this->kv->delete('test/my/key');

        try {
            $this->kv->get('test/my/key');
            $this->fail('fail because the key does not exist anymore.');
        } catch (Exception $e) {
            $this->assertInstanceOf('SensioLabs\Consul\Exception\ClientException', $e);
            $this->assertContains('404 - Not Found', $e->getMessage());
        }
    }

    public function testDeleteWithRecurseOption()
    {
        $this->kv->put('test/my/key1', 'hello 1');
        $this->kv->put('test/my/key2', 'hello 2');
        $this->kv->put('test/my/key3', 'hello 3');

        $this->kv->get('test/my/key1');
        $this->kv->get('test/my/key2');
        $this->kv->get('test/my/key3');

        $this->kv->delete('test/my', array('recurse' => true));

        for ($i = 1; $i < 3; $i++) {
            try {
                $this->kv->get('test/my/key' . $i);
                $this->fail('fail because the key does not exist anymore.');
            } catch (Exception $e) {
                $this->assertInstanceOf('SensioLabs\Consul\Exception\ClientException', $e);
                $this->assertContains('404 - Not Found', $e->getMessage());
            }
        }
    }
}