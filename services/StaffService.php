<?php

namespace app\services;

use app\models\Interview;
use app\repositories\InterviewRepository;
use Yii;
use app\models\Log;

class  StaffService
{
    private $interviewRepository;

    public function __construct(InterviewRepository $interviewRepository)
    {
        $this->interviewRepository = $interviewRepository;
    }

    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        // используем статический метод для создания обьекта, а не конструктор потому, что:
        // 1. мы испльзуем ActiveREcord, а он не будет работать с конструкторами.
        // 2. может быть несколько способов создания одного и того же обьекта, тогда одного конструктора не хватит.
        $interview = Interview::join($lastName, $firstName, $email, $date);
        $this->interviewRepository->add($interview);

        $this->notify($interview->email, 'You are joined to interview!');
        $this->log($interview->last_name . ' ' . $interview->first_name . ' is joined to interview');

        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->editData($lastName, $firstName, $email);
        $this->interviewRepository->save($interview);

        $this->log('Interview' . $interview->id . ' is updated');
    }

    public function moveInterview($id, $date)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->move($date);
        $this->interviewRepository->save($interview);

        $this->notify($interview->email, 'You interview is moved');
        $this->log('Interview ' . $interview->id . ' is move on ' . $interview->date);
    }

    public function rejectInterview($id, $reason)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->reject($reason);
        $this->interviewRepository->save($interview);

        $this->notify($interview->email, 'You are failed an interview');
        $this->log($interview->last_name . ' ' . $interview->first_name . ' is failed an interview');
    }

    public function deleteInterview($id)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->remove(); // Сюда можно вставить логику, которая должна произойти при удалении.
        $this->interviewRepository->delete($interview);

        $this->log('Interview ' . $interview->id . ' is removed');
    }

    private function log($message)
    {
        $log = new Log();
        $log->message = $message;
        $log->save();
    }

    private function notify($email, $message)
    {
        if ($email) {
                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo($email)
                    ->setSubject($message)
                    ->send();
        }
    }
}