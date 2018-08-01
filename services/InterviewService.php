<?php

namespace app\services;

use app\models\Interview;
use app\repositories\InterviewRepositoryInterface;
use app\dispatchers\EventDispatcherInterface;
use app\events\interview\InterviewJoinEvent;

class InterviewService
{
    private $interviewRepository;
    private $eventDispatcher;

    public function __construct(
        InterviewRepositoryInterface $interviewRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->interviewRepository = $interviewRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function join($lastName, $firstName, $email, $date)
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

    public function edit($id, $lastName, $firstName, $email)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->editData($lastName, $firstName, $email);
        $this->interviewRepository->save($interview);
    }

    public function move($id, $date)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->move($date);
        $this->interviewRepository->save($interview);
    }

    public function reject($id, $reason)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->reject($reason);
        $this->interviewRepository->save($interview);
    }

    public function delete($id)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->remove(); // Сюда можно вставить логику, которая должна произойти при удалении.
        $this->interviewRepository->delete($interview);
    }
}