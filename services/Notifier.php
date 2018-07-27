<?php
namespace app\services;

use Yii;

class Notifier implements NotifierInterface
{
    private $fromEmail;

    public function __construct($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    public function notify($email, $message)
    {
        if ($email) {
            Yii::$app->mailer->compose()
                ->setFrom($this->fromEmail)
                ->setTo($email)
                ->setSubject($message)
                ->send();
        }
    }
}