<?php
namespace app\events\employee;

use app\events\Event;
use app\events\LoggableEvent;
use app\models\Employee;
use app\models\Interview;

class EmployeeRecruitByInterviewEvent extends Event implements LoggableEvent
{
    public $employee;
    public $interview;

    public function __construct(Employee $employee, Interview $interview)
    {
        $this->employee = $employee;
        $this->interview = $interview;
    }

    public function getLogMessage()
    {
        return 'Employee' . $this->employee->id . ' is recruited by interview ' . $this->interview->id;
    }
}