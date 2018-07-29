<?php
namespace app\events;

interface LoggableEvent
{
    public function getLogMessage();
}