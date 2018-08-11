<?php
namespace app\bootstrap;

use app\dispatchers\LoggedEventDispatcher;
use app\dispatchers\SimpleEventDispatcher;
use app\services\Notifier;
use yii\base\BootstrapInterface;
use Yii;
use yii\di\Container;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton('app\services\NotifierInterface', function () use ($app) {
            return new Notifier($app->params['adminEmail']);
        });
        $container->setSingleton('app\repositories\InterviewRepositoryInterface', 'app\repositories\InterviewRepository');
        $container->setSingleton('app\repositories\ContractRepositoryInterface', 'app\repositories\ContractRepository');
        $container->setSingleton('app\repositories\EmployeeRepositoryInterface', 'app\repositories\EmployeeRepository');
        $container->setSingleton('app\repositories\RecruitRepositoryInterface', 'app\repositories\RecruitRepository');
        $container->setSingleton('app\repositories\PositionRepositoryInterface', 'app\repositories\PositionRepository');
        $container->setSingleton('app\repositories\AssignmentRepositoryInterface', 'app\repositories\AssignmentRepository');
        $container->setSingleton('app\services\LoggerInterface', 'app\services\Logger');

        // Навещиваем обработчики на события.
        $container->setSingleton('app\dispatchers\EventDispatcherInterface', function (Container $container) {
           return new LoggedEventDispatcher(
               new SimpleEventDispatcher([
                   'app\events\interview\InterviewJoinEvent' => ['app\listeners\interview\InterviewJoinListener'],
                   'app\events\interview\InterviewMoveEvent' => ['app\listeners\interview\InterviewMoveListener'],
                   'app\events\interview\InterviewRejectEvent' => ['app\listeners\interview\InterviewRejectListener'],
               ]),
               $container->get('app\services\LoggerInterface')
           );
        });
    }
}