<?php
namespace app\dispatchers;

use app\events\Event;
use app\events\LoggableEvent;
use app\services\LoggerInterface;

class LoggedEventDispatcher implements EventDispatcherInterface
{
    private $next; // следующий диспетчер
    private $logger;

    public function __construct(EventDispatcherInterface $next, LoggerInterface $logger)
    {
        $this->next = $next;
        $this->logger = $logger;
    }

    public function dispatch(Event $event)
    {
        // Реализция декоратора
        // (дергает следующий диспетчер и логирует).
        // P.S при таком подходе можно каждый диспетчер тестировать отдельно.
        $this->next->dispatch($event);
        if ($event instanceof LoggableEvent) {
            $this->logger->log($event->getLogMessage());
        }
    }
}