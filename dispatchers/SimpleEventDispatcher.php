<?php
namespace app\dispatchers;

use app\events\Event;

class SimpleEventDispatcher
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