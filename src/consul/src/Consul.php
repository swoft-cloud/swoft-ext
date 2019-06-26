<?php declare(strict_types=1);


namespace Swoft\Consul;

use ReflectionException;
use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Log\Helper\Log;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\JsonHelper;
use Throwable;
use Swlib\Saber\Response as SaberResponse;

/**
 * Class Consul
 *
 * @since 2.0
 *
 * @Bean()
 */
class Consul
{
    /**
     * @var string
     */
    private $host = 'http://127.0.0.1';

    /**
     * @var int
     */
    private $port = 8500;

    /**
     * @param string|null $url
     * @param array       $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function get(string $url = null, array $options = []): Response
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function head(string $url, array $options = []): Response
    {
        return $this->request('HEAD', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function delete(string $url, array $options = []): Response
    {
        return $this->request('DELETE', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function put(string $url, array $options = []): Response
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function patch(string $url, array $options = []): Response
    {
        return $this->request('PATCH', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function post(string $url, array $options = []): Response
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return Response
     * @throws ClientException
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServerException
     */
    public function options(string $url, array $options = []): Response
    {
        return $this->request('OPTIONS', $url, $options);
    }

    /**
     * @param $method
     * @param $uri
     * @param $options
     *
     * @return Response
     * @throws ClientException
     * @throws ServerException
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function request($method, $uri, $options): Response
    {
        if (isset($options['body']) && is_array($options['body'])) {
            $options['body'] = json_encode($options['body']);
        }

        Log::debug('Requesting %s %s %s', $method, $uri, JsonHelper::encode($options));

        try {
            Log::profileStart($uri);

            $baseOption = [
                'base_uri' => sprintf('%s:%d', $this->host, $this->port),
                'method'   => $method,
                'uri'      => $uri
            ];

            $options  = ArrayHelper::merge($baseOption, $options);
            $response = SaberGM::request($options);
            Log::profileStart($uri);
        } catch (Throwable $e) {
            $message = sprintf('Something went wrong when calling consul (%s).', $e->getMessage());
            Log::error($message);
            throw new ServerException($message);
        }

        Log::debug("Response:\n%s", $this->formatResponse($response));

        if (400 <= $response->getStatusCode()) {
            $message = sprintf('Something went wrong when calling consul (%s - %s).', $response->getStatusCode(),
                $response->getReasonPhrase());

            Log::error($message);

            $message .= "\n" . (string)$response->getBody();
            if (500 <= $response->getStatusCode()) {
                throw new ServerException($message, $response->getStatusCode());
            }

            throw new ClientException($message, $response->getStatusCode());
        }

        return Response::new($response->getHeaders(), (string)$response->getBody(), $response->getStatusCode());
    }

    /**
     * @param SaberResponse $response
     *
     * @return string
     */
    private function formatResponse(SaberResponse $response): string
    {
        $headers = [];

        foreach ($response->getHeaders() as $key => $values) {
            foreach ($values as $value) {
                $headers[] = sprintf('%s: %s', $key, $value);
            }
        }

        return sprintf("%s\n\n%s", implode("\n", $headers), $response->getBody());
    }
}