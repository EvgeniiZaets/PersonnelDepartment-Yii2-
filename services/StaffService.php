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
        $interview->save(false); // Не запускаем валдацию т.к. данные ужо провалидированы с помощью InteviewJoinForm.

        if ($interview->email) {
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($interview->email)
                ->setSubject('You are joined to interview!')
                ->send();
        }

        $log = new Log();
        $log->message = $interview->last_name . ' ' . $interview->first_name . ' is joined to interview';
        $log->save();

        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        if (!$interview = Interview::findOne($id))
            throw new \DomainException('Interview not found');

        $interview->editData($lastName, $firstName, $email);
        $interview->save(false);

        $log = new Log();
        $log->message = 'Interview' . $interview->id . ' is updated';
        $log->save();
    }

    public function rejectInterview($id, $reason)
    {
        if (!$interview = Interview::findOne($id))
            throw new \DomainException('Interview not found');

        $interview->reject($reason);
        $interview->save(false);

        if ($interview->email) {
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($interview->email)
                ->setSubject('You are failed an interview')
                ->send();
        }

        $log = new Log();
        $log->message = $interview->last_name . ' ' . $interview->first_name . ' is failed an interview';
        $log->save();
    }
}