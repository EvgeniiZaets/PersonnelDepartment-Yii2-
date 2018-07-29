<?php
namespace app\listeners;

use app\events\interview\InterviewJoinEvent;
use app\services\NotifierInterface;

class InterviewJoinListener
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(InterviewJoinEvent $event)
    {
        $this->notifier->notify($event->interview->email, 'You are joined to interview!');
    }
}