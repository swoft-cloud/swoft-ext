<?php declare(strict_types=1);

namespace Swoft\Crontab\Annotaion\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
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
    public function parse(int $type, $annotationObject): array
    {
        /* @var Scheduled $annotationObject */
        $beanName = $this->className;
        $name = $annotationObject->getName();
        if (!empty($name)) {
            $beanName = $name;
        }
        CrontabRegister::registerScheduled($this->className, $beanName);
        return [];
    }
}
