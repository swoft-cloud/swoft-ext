<?php declare(strict_types=1);


namespace Swoft\Consul\Contract;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class HealthInterface
 *
 * @since 2.0
 *
 * @Bean()
 */
interface HealthInterface
{
    public function node(string $node, array $options = array());

    public function checks(string $service, array $options = array());

    public function service(string $service, array $options = array());

    public function state(string $state, array $options = array());
}