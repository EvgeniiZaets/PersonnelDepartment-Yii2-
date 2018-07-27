<?php

namespace app\services;

use app\models\Interview;
use app\repositories\InterviewRepository;

class  StaffService
{
    private $interviewRepository;
    private $logger;
    private $notifier;

    public function __construct(InterviewRepository $interviewRepository, LoggerInterface $logger, NotifierInterface $notifier)
    {
        $this->interviewRepository = $interviewRepository;
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        // используем статический метод для создания обьекта, а не конструктор потому, что:
        // 1. мы испльзуем ActiveREcord, а он не будет работать с конструкторами.
        // 2. может быть несколько способов создания одного и того же обьекта, тогда одного конструктора не хватит.
        $interview = Interview::join($lastName, $firstName, $email, $date);
        $this->interviewRepository->add($interview);

        $this->notifier->notify($interview->email, 'You are joined to interview!');
        $this->logger->log($interview->last_name . ' ' . $interview->first_name . ' is joined to interview');

        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->editData($lastName, $firstName, $email);
        $this->interviewRepository->save($interview);

        $this->logger->log('Interview' . $interview->id . ' is updated');
    }

    public function moveInterview($id, $date)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->move($date);
        $this->interviewRepository->save($interview);

        $this->notifier->notify($interview->email, 'You interview is moved');
        $this->logger->log('Interview ' . $interview->id . ' is move on ' . $interview->date);
    }

    public function rejectInterview($id, $reason)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->reject($reason);
        $this->interviewRepository->save($interview);

        $this->notifier->notify($interview->email, 'You are failed an interview');
        $this->logger->log($interview->last_name . ' ' . $interview->first_name . ' is failed an interview');
    }

    public function deleteInterview($id)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->remove(); // Сюда можно вставить логику, которая должна произойти при удалении.
        $this->interviewRepository->delete($interview);

        $this->logger->log('Interview ' . $interview->id . ' is removed');
    }
}