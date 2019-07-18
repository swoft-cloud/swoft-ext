<?php
/** For Swoole coroutine tests */

use Swoole\ExitException;

Co::set([
    'log_level'   => SWOOLE_LOG_INFO,
    'trace_flags' => 0
]);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (version_compare('7.1.0', PHP_VERSION, '>')) {
    fwrite(
        STDERR,
        sprintf(
            'This version of PHPUnit is supported on PHP 7.1 and PHP 7.2.' . PHP_EOL .
            'You are using PHP %s (%s).' . PHP_EOL,
            PHP_VERSION,
            PHP_BINARY
        )
    );
    die(1);
}
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}
foreach ([
             __DIR__ . '/../../autoload.php',
             __DIR__ . '/../vendor/autoload.php',
             __DIR__ . '/vendor/autoload.php'
         ] as $file
) {
    if (file_exists($file)) {
        define('PHPUNIT_COMPOSER_INSTALL', $file);
        break;
    }
}
unset($file);
if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    fwrite(
        STDERR,
        'You need to set up the project dependencies using Composer:' . PHP_EOL . PHP_EOL .
        '        composer install' . PHP_EOL . PHP_EOL .
        'You can learn all about Composer on https://getcomposer.org/.' . PHP_EOL
    );
    die(1);
} else {
    if (array_reverse(explode('/', __DIR__))[0] ?? '' === 'tests') {
        $vendor_dir = dirname(PHPUNIT_COMPOSER_INSTALL);
        $bin_unit   = "{$vendor_dir}/bin/phpunit";
        $unit_uint  = "{$vendor_dir}/phpunit/phpunit/phpunit";
        if (file_exists($bin_unit)) {
            @unlink($bin_unit);
            @symlink(__FILE__, $bin_unit);
        }
        if (file_exists($unit_uint)) {
            @unlink($unit_uint);
            @symlink(__FILE__, $unit_uint);
        }
    }
}
if (!in_array('-c', $_SERVER['argv'])) {
    $_SERVER['argv'][] = '-c';
    $_SERVER['argv'][] = __DIR__ . '/phpunit.xml';
}
require PHPUNIT_COMPOSER_INSTALL;

$status = 0;
go(function (){
    // Status
    global $status;

    try {
        $status = PHPUnit\TextUI\Command::main(false);
    } catch (ExitException $e) {
        var_dump($e->getMessage());
        $status = $e->getCode();
    }
});


Swoole\Event::wait();

exit($status);
