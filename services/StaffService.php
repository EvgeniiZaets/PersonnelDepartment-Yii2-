<?php

namespace app\services;

use app\models\Contract;
use app\models\Employee;
use app\models\Interview;
use app\repositories\ContractRepositoryInterface;
use app\repositories\EmployeeRepositoryInterface;
use app\repositories\InterviewRepositoryInterface;
use app\dispatchers\EventDispatcherInterface;
use app\events\interview\InterviewJoinEvent;
use app\repositories\PositionRepositoryInterface;
use app\repositories\RecruitRepositoryInterface;
use app\services\dto\RecruitData;

class  StaffService
{
    private $contractRepository;
    private $employeeRepository;
    private $interviewRepository;
    private $positionRepository;
    private $recruitRepository;
    private $eventDispatcher;

    public function __construct(
        ContractRepositoryInterface $contractRepository,
        EmployeeRepositoryInterface $employeeRepository,
        InterviewRepositoryInterface $interviewRepository,
        PositionRepositoryInterface $positionRepository,
        RecruitRepositoryInterface $recruitRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->contractRepository = $contractRepository;
        $this->employeeRepository = $employeeRepository;
        $this->interviewRepository = $interviewRepository;
        $this->positionRepository = $positionRepository;
        $this->recruitRepository = $recruitRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        // используем статический метод для создания обьекта, а не конструктор потому, что:
        // 1. мы испльзуем ActiveREcord, а он не будет работать с конструкторами.
        // 2. может быть несколько способов создания одного и того же обьекта, тогда одного конструктора не хватит.
        $interview = Interview::join($lastName, $firstName, $email, $date);
        $this->interviewRepository->add($interview);
        // При вызове dispatch(), диспетчер автоматически по имени класса
        // циклом пройдет по всем привязанным к этому событию обработчикам
        // и вызовет каждый, передаваю туда InterviewJoinEvent.
        $this->eventDispatcher->dispatch(new InterviewJoinEvent($interview));

        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->editData($lastName, $firstName, $email);
        $this->interviewRepository->save($interview);
    }

    public function moveInterview($id, $date)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->move($date);
        $this->interviewRepository->save($interview);
    }

    public function rejectInterview($id, $reason)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->reject($reason);
        $this->interviewRepository->save($interview);
    }

    public function deleteInterview($id)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->remove(); // Сюда можно вставить логику, которая должна произойти при удалении.
        $this->interviewRepository->delete($interview);
    }

    public function createEmployee(RecruitData $recruitData, $orderDate, $contractDate, $recruitDate)
    {
        // Заполняем сотрудника данными. (что бы не заполнять снаружи)
        $employee = Employee::create(
            $recruitData->firstName,
            $recruitData->lastName,
            $recruitData->address,
            $recruitData->email
        );

        // TODO:do
//        $contract = Contract::create($employee, $recruitData->lastName, $recruitData->firstName, $contractDate);
    }
}