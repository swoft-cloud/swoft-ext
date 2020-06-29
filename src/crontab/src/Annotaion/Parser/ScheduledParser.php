<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Crontab\Annotaion\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Crontab\CrontabRegister;

/**
 * Class ScheduledParser
 *
 * @since 2.0
 *
 * @AnnotationParser(annotation=Scheduled::class)
 */
class ScheduledParser extends Parser
{
    /**
     * @param int       $type
     * @param Scheduled $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        $beanName = $this->className;
        $name     = $annotationObject->getName();

        if (!empty($name)) {
            $beanName = $name;
        }

        CrontabRegister::registerScheduled($this->className, $beanName);
        return [$beanName, $this->className, Bean::SINGLETON, ''];
    }
}
