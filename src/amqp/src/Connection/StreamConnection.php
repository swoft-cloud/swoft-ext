<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Amqp\Connection;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class StreamConnection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class StreamConnection extends Connection
{
    /**
     * @param array $auths
     * @param array $options
     *
     * @return mixed
     * @throws Exception
     */
    public function connect(array $auths, array $options)
    {
        return AMQPStreamConnection::create_connection($auths, $options);
    }
}
