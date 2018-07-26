<?php

namespace app\services;

use app\models\Interview;
use Yii;
use app\models\Log;

class   StaffService
{
    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        // используем статический метод для создания обьекта, а не конструктор потому, что:
        // 1. мы испльзуем ActiveREcord, а он не будет работать с конструкторами.
        // 2. может быть несколько способов создания одного и того же обьекта, тогда одного конструктора не хватит.
        $interview = Interview::join($lastName, $firstName, $email, $date);
        $this->save($interview);

        $this->notify($interview->email, 'You are joined to interview!');
        $this->log($interview->last_name . ' ' . $interview->first_name . ' is joined to interview');

        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        $interview = $this->findInterviewModel($id);
        $interview->editData($lastName, $firstName, $email);
        $this->save($interview);

        $this->log('Interview' . $interview->id . ' is updated');
    }

    public function rejectInterview($id, $reason)
    {
        $interview = $this->findInterviewModel($id);
        $interview->reject($reason);
        $this->save($interview);

        $this->notify($interview->email, 'You are failed an interview');
        $this->log($interview->last_name . ' ' . $interview->first_name . ' is failed an interview');
    }

    /**
     * @param $id
     * @return Interview
     */
    private function findInterviewModel($id)
    {
        if (!$interview = Interview::findOne($id))
            throw new \DomainException('Interview not found');

        return $interview;
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

    /**
     * @param $interview
     */
    private function save(Interview $interview)
    {
        // Не запускаем валдацию т.к. данные ужо провалидированы с помощью InteviewJoinForm.
        if (!$interview->save(false))
            throw new \RuntimeException('Saving error.');
    }
}