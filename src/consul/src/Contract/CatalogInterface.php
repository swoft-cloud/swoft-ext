<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Consul\Contract;

use Swoft\Consul\Response;

/**
 * Class CatalogInterface
 *
 * @since 2.0
 */
interface CatalogInterface
{
    /**
     * @param array $node
     *
     * @return Response
     */
    public function register(array $node): Response;

    /**
     * @param array $node
     *
     * @return Response
     */
    public function deregister(array $node): Response;

    /**
     * @return Response
     */
    public function datacenters(): Response;

    /**
     * @param array $options
     *
     * @return Response
     */
    public function nodes(array $options = []): Response;

    /**
     * @param string $node
     * @param array  $options
     *
     * @return Response
     */
    public function node(string $node, array $options = []): Response;

    /**
     * @param array $options
     *
     * @return Response
     */
    public function services(array $options = []): Response;

    /**
     * @param string $service
     * @param array  $options
     *
     * @return Response
     */
    public function service(string $service, array $options = []): Response;
}
