<?php

namespace app\services;

use app\events\employee\EmployeeRecruitByInterviewEvent;
use app\events\employee\EmployeeRecruitEvent;
use app\models\Contract;
use app\models\Employee;
use app\models\Order;
use app\models\Recruit;
use app\repositories\ContractRepositoryInterface;
use app\repositories\EmployeeRepositoryInterface;
use app\repositories\InterviewRepositoryInterface;
use app\dispatchers\EventDispatcherInterface;
use app\repositories\RecruitRepositoryInterface;
use app\services\dto\RecruitData;

class  EmployeeService
{
    private $contractRepository;
    private $employeeRepository;
    private $interviewRepository;
    private $recruitRepository;
    private $eventDispatcher;
    private $transactionManager;

    public function __construct(
        ContractRepositoryInterface $contractRepository,
        EmployeeRepositoryInterface $employeeRepository,
        InterviewRepositoryInterface $interviewRepository,
        RecruitRepositoryInterface $recruitRepository,
        EventDispatcherInterface $eventDispatcher,
        TransactionManager $transactionManager
    )
    {
        $this->contractRepository = $contractRepository;
        $this->employeeRepository = $employeeRepository;
        $this->interviewRepository = $interviewRepository;
        $this->recruitRepository = $recruitRepository;
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
        $transaction = $this->transactionManager->begin();
        try {
            $this->employeeRepository->add($employee);
            $this->contractRepository->add($contract);
            $this->recruitRepository->add($recruit);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
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
        $transaction = $this->transactionManager->begin();
        try {
            $this->interviewRepository->save($interview);
            $this->employeeRepository->add($employee);
            $this->contractRepository->add($contract);
            $this->recruitRepository->add($recruit);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->eventDispatcher->dispatch(new EmployeeRecruitByInterviewEvent($employee, $interview));
        return $employee;
    }
}