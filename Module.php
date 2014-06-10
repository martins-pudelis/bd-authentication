<?php
namespace BdAuthentication;

use BdAuthentication\Event\AuthenticationEvent;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\StaticEventManager;
use Zend\Session\Container;


class Module
{
    /*public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }*/

    /**
     *
     */
    public function init()
    {
        // Attach Event to EventManager
        $events = StaticEventManager::getInstance();

        $events->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            'dispatch',
            array($this, 'authenticationPreDispatch'),
            100
        );
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * MVC preDispatch Event
     *
     * @param $event
     * @return mixed
     */
    public function authenticationPreDispatch(MvcEvent $event)
    {
        $serviceLocator = $event->getTarget()->getServiceLocator();

        $eventHandler = new AuthenticationEvent();
        $eventHandler->setAuthService($serviceLocator->get('AuthService'));

        $eventHandler->preDispatch($event);
    }

    /**
     * @return string
     */
    protected function getModuleNamespace()
    {
        return __NAMESPACE__;
    }
}