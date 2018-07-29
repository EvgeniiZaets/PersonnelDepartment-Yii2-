<?php
namespace app\events\interview;

use app\events\Event;
use app\events\LoggableEvent;
use app\models\Interview;

class InterviewMoveEvent extends Event implements LoggableEvent
{
    public $interview;

    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    public function getLogMessage()
    {
        return 'Interview ' . $this->interview->id . ' is move on ' . $this->interview->date;
    }
}