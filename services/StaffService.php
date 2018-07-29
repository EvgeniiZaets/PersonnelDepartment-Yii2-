<?php

namespace app\services;

use app\models\Interview;
use app\repositories\InterviewRepositoryInterface;

class Event
{

}

class InterviewJoinEvent extends Event
{
    public $interview;

    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }
}

class InterviewJoinEventListener
{
    public function handle(InterviewJoinEvent $event)
    {
        echo $event->interview->id;
    }
}

class EventDispatcher
{
    private $listeners = []; // содержит обработчики событий

    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }

    // Ищет по имени класса $event обработчики в массиве $this->listeners и вызывет их.
    public function dispatch(Event $event)
    {
        $eventName = get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $className) {
                $listener = \Yii::$container->get($className);
                $listener->handle($event); // Обрабатывает $event
            }
        }
    }
}

// Навещиваем обработчики на события.
$eventDispatcher = new EventDispatcher([
    'app\services\InterviewJoinEvent' => [
        'app\services\InterviewJoinEventListener'
    ]
]);

class  StaffService
{
    private $interviewRepository;
    private $logger;
    private $notifier;
    private $eventDispatcher; // содержит обработчики событий

    public function __construct(
        InterviewRepositoryInterface $interviewRepository,
        LoggerInterface $logger,
        NotifierInterface $notifier,
        EventDispatcher $eventDispatcher
    )
    {
        $this->interviewRepository = $interviewRepository;
        $this->logger = $logger;
        $this->notifier = $notifier;
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