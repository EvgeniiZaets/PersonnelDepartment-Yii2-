<?php
namespace app\services;

use Yii;

class Notifier implements NotifierInterface
{
    public function notify($email, $message)
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