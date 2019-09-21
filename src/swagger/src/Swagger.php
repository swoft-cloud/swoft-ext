<?php declare(strict_types=1);


namespace Swoft\Swagger;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Router\Router;
use Swoft\Http\Server\Router\RouteRegister;
use Swoft\Swagger\Annotation\Mapping\ApiOperation;
use Swoft\Swagger\Annotation\Mapping\ApiRequestBody;
use Swoft\Swagger\Annotation\Mapping\ApiResponse;
use Swoft\Swagger\Annotation\Mapping\ApiServer;
use Swoft\Swagger\Exception\SwaggerException;
use Swoft\Swagger\Node\Info;
use Swoft\Swagger\Node\OpenApi;
use Swoft\Swagger\Node\Operation;
use Swoft\Swagger\Node\PathItem;
use Swoft\Swagger\Node\Paths;

/**
 * Class Swagger
 *
 * @since 2.0
 *
 * @Bean()
 */
class Swagger
{
    /**
     * @throws SwaggerException
     */
    public function gen(): void
    {
        $openapi = $this->createRootNode();

        var_dump($openapi->toJson());
    }

    /**
     * @return OpenApi
     * @throws SwaggerException
     */
    private function createRootNode(): OpenApi
    {
        $info    = $this->createInfoNode();
        $servers = ApiRegister::getServers();
        $paths   = $this->createPaths();

        $openApi = new OpenApi();
        $openApi->setInfo($info);
        $openApi->setServers($servers);
        $openApi->setPaths($paths);

        return $openApi;
    }

    /**
     * @return Info
     */
    private function createInfoNode(): Info
    {
        $contract = ApiRegister::getContract();
        $license  = ApiRegister::getLicense();

        $info = ApiRegister::getInfo();
        $info->setContact($contract);
        $info->setLicense($license);

        return $info;
    }

    /**
     * @return Paths
     * @throws SwaggerException
     */
    private function createPaths(): Paths
    {
        /** @var Router $router Register HTTP routes */
        $router = bean('httpRouter');
        $routes = $router->getRoutes();

        $handlerRoutes = [];
        foreach ($routes as $route) {
            $handler                   = $route['handler'];
            $handlerRoutes[$handler]['handlers'][] = $route;
            $handlerRoutes[$handler]['path'][] = $route['path'];

        }

        $paths         = [];
        $registerPaths = ApiRegister::getPaths();
        foreach ($registerPaths as $className => $methodPaths) {
            foreach ($methodPaths as $methodName => $path) {
                $pathHandler = sprintf('%s@%s', $className, $methodName);
                if (!isset($handlerRoutes[$pathHandler])) {
                    throw new SwaggerException(sprintf(
                        'The %s of %s must be define `@RequestMapping`', $className, $methodName
                    ));
                }

                $pathRouter    = $handlerRoutes[$pathHandler];
                $rPath         = $pathRouter['path'];
                $paths[$rPath] = $this->createPathItem($path, $pathRouter);
            }
        }

        return new Paths($paths);
    }

    /**
     * @param array $path
     * @param array $pathRouter
     *
     * @return PathItem
     */
    private function createPathItem(array $path, array $pathRouter): PathItem
    {
        $data = [];
        foreach ($pathRouter as $handler => $handlers) {
            foreach ($handlers as $handler) {
                $method = $handler['method'];
                $method = strtolower($method);

                $data[$method] = $this->createOperation($path, $handler);
            }
        }

        return new PathItem($data);
    }

    /**
     * @param array $path
     * @param array $handler
     *
     * @return Operation
     */
    private function createOperation(array $path, array $handler): Operation
    {
        /* @var ApiOperation $operation */
        $operation = $path['operation'];

        /* @var ApiResponse $response */
        $response = $path['response'];

        /* @var ApiRequestBody $requestBody */
        $requestBody = $path['requestBody'];

        /* @var ApiServer[] $servers */
        $servers = $path['servers'];

        $method = $handler['method'];
        $method = strtolower($method);

        $data = [
            'tags'        => $operation->getTags(),
            'summary'     => $operation->getSummary(),
            'description' => $operation->getDescription(),
            'operationId' => $operation->getOperationId(),
        ];

        return new Operation($data);
    }
}