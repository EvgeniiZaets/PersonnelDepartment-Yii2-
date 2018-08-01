<?php
namespace app\events\employee;

use app\events\Event;
use app\events\LoggableEvent;
use app\models\Assignment;

class EmployeeAssignEvent extends Event implements LoggableEvent
{
    public $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function getLogMessage()
    {
        return 'Employee' . $this->assignment->employee->id . ' is assigned on position '
            . $this->assignment->position->id . ' with assignment '. $this->assignment->id;
    }
}