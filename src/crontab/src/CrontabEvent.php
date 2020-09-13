<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Crontab;

/**
 * Class CrontabEvent
 *
 * @since 2.0
 */
class CrontabEvent
{
    /**
     * Before
     */
    public const BEFORE_CRONTAB = 'swoft.crontab.crontab.before';

    /**
     * After
     */
    public const AFTER_CRONTAB = 'swoft.crontab.crontab.after';
}
