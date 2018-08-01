<?php

namespace app\services;

use app\events\employee\EmployeeAssignEvent;
use app\events\employee\EmployeeRecruitByInterviewEvent;
use app\events\employee\EmployeeRecruitEvent;
use app\models\Assignment;
use app\models\Contract;
use app\models\Employee;
use app\models\Order;
use app\models\Recruit;
use app\repositories\AssignmentRepositoryInterface;
use app\repositories\ContractRepositoryInterface;
use app\repositories\EmployeeRepositoryInterface;
use app\repositories\InterviewRepositoryInterface;
use app\dispatchers\EventDispatcherInterface;
use app\repositories\PositionRepositoryInterface;
use app\repositories\RecruitRepositoryInterface;
use app\services\dto\RecruitData;

class  EmployeeService
{
    private $contractRepository;
    private $employeeRepository;
    private $interviewRepository;
    private $recruitRepository;
    private $positionRepository;
    private $assignmentRepository;
    private $eventDispatcher;
    private $transactionManager;

    public function __construct(
        ContractRepositoryInterface $contractRepository,
        EmployeeRepositoryInterface $employeeRepository,
        InterviewRepositoryInterface $interviewRepository,
        RecruitRepositoryInterface $recruitRepository,
        PositionRepositoryInterface $positionRepository,
        AssignmentRepositoryInterface $assignmentRepository,
        EventDispatcherInterface $eventDispatcher,
        TransactionManager $transactionManager
    )
    {
        $this->contractRepository = $contractRepository;
        $this->employeeRepository = $employeeRepository;
        $this->interviewRepository = $interviewRepository;
        $this->recruitRepository = $recruitRepository;
        $this->positionRepository = $positionRepository;
        $this->assignmentRepository = $assignmentRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->transactionManager = $transactionManager;
    }

    public function create(RecruitData $recruitData, $orderDate, $contractDate, $recruitDate)
    {
        // Заполняем сотрудника данными. (что бы не заполнять снаружи)
        $employee = Employee::create(
            $recruitData->firstName,
            $recruitData->lastName,
            $recruitData->address,
            $recruitData->email
        );
        $contract = Contract::create($employee, $recruitData->lastName, $recruitData->firstName, $contractDate);
        $recruit = Recruit::create($employee, Order::create($orderDate), $recruitDate);
        $this->transactionManager->execute(function () use ($employee, $contract, $recruit) {
            $this->employeeRepository->add($employee);
            $this->contractRepository->add($contract);
            $this->recruitRepository->add($recruit);
        });
        $this->eventDispatcher->dispatch(new EmployeeRecruitEvent($employee));
        return $employee;
    }

    public function createByInterview($interviewId, RecruitData $recruitData, $orderDate, $contractDate, $recruitDate)
    {
        $interview = $this->interviewRepository->find($interviewId);
        $employee = Employee::create(
            $recruitData->firstName,
            $recruitData->lastName,
            $recruitData->address,
            $recruitData->email
        );
        $interview->passBy($employee);
        $contract = Contract::create($employee, $recruitData->lastName, $recruitData->firstName, $contractDate);
        $recruit = Recruit::create($employee, Order::create($orderDate), $recruitDate);
        $this->transactionManager->execute(function () use ($interview, $employee, $contract, $recruit) {
            $this->interviewRepository->save($interview);
            $this->employeeRepository->add($employee);
            $this->contractRepository->add($contract);
            $this->recruitRepository->add($recruit);
        });
        $this->eventDispatcher->dispatch(new EmployeeRecruitByInterviewEvent($employee, $interview));
        return $employee;
    }

    public function assignToPosition($employeeId, $positionId, $rate, $salary, $orderDate, $startDate)
    {
        $employee = $this->employeeRepository->find($employeeId);
        $position = $this->positionRepository->find($positionId);

        $assignment = Assignment::create(Order::create($orderDate), $employee, $position, $startDate, $rate, $salary);

        $this->assignmentRepository->add($assignment);
        $this->eventDispatcher->dispatch(new EmployeeAssignEvent($assignment));
    }
}