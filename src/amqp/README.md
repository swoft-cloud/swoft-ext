# Swoft Amqp

[![Latest Stable Version](http://img.shields.io/packagist/v/swoft/amqp.svg)](https://packagist.org/packages/swoft/amqp)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Swoft Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://www.swoft.org/docs)
[![Swoft License](https://img.shields.io/hexpm/l/plug.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/blob/master/LICENSE)

Swoft Amqp Component

## Install

- composer command

```bash
composer require swoft/amqp
```

## Resources

* [Documentation](https://swoft.org/docs)
* [Contributing](https://github.com/swoft-cloud/swoft/blob/master/CONTRIBUTING.md)
* [Report Issues][issues] and [Send Pull Requests][pulls] in the [Main Swoft Repository][repository]

[pulls]: https://github.com/swoft-cloud/swoft-component/pulls
[repository]: https://github.com/swoft-cloud/swoft
[issues]: https://github.com/swoft-cloud/swoft/issues

## Config

- app/bean.php

```php
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Swoft\Amqp\Pool;
use Swoft\Amqp\AmqpDb;

return [
    'amqp' => [
        'class'    => AmqpDb::class,
        'auths'    => [
            [
                'host'     => '127.0.0.1',
                'port'     => 5672,
                'user'     => 'admin',
                'password' => 'admin',
                'vhost'    => '/',
            ],
        ],
        'exchange' => [
            'name' => 'exchange_name',
            'type' => AMQPExchangeType::TOPIC
        ],
        'queue'    => [
            'name' => 'queue_name'
        ],
        'route'    => [],
    ],
    'amqp.pool'         => [
        'class'  => Pool::class,
        'amqpDb' => bean('amqp'),
    ]
]
```

## Example
- push message into queue
```php
use Swoft\Amqp\Amqp;

Amqp::push('test message', [ 'property_key' => 'property_value' ], '/');
```

- get message from queue
```php
use Swoft\Amqp\Amqp;

$message = Amqp::pop();
```

- listen the queue
```php
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Amqp\Amqp;

Amqp::consume(function(AMQPMessage $message) {
    echo $message->body;
});
```

- connect to another amqp pool
```php
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Amqp\Amqp;

$connection = Amqp::connection('another_amqp_pool');
$connection->push('message');
$connection->pop();
$connection->consume(function(AMQPMessage $message) {
    echo $message->body;
});
```

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).
