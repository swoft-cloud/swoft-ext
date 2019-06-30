<?php declare(strict_types=1);


namespace Swoft\Apollo\Contract;

/**
 * Class ConfigInterface
 *
 * @since 2.0
 */
interface ConfigInterface
{
    public function pullWithCache(string $namespace, string $clientIp = ''): array;

    public function pull(string $namespace, string $releaseKey = ''): array;

    public function listen($callback, array $namespaces, array $notifications = [], string $clientIp = ''): void;
}