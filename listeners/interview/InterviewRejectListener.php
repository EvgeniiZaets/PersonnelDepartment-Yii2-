<?php
namespace app\listeners\interview;

use app\events\interview\InterviewJoinEvent;
use app\services\NotifierInterface;

class InterviewRejectListener
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(InterviewJoinEvent $event)
    {
        $this->notifier->notify($event->interview->email, 'You are failed an interview');
    }
}