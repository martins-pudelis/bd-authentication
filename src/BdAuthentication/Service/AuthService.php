<?php

namespace BdAuthentication\Service;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use BdGeneric\GenericDoctrineProvider;
use BdAuthentication\AuthenticationProviderInterface;

class AuthService implements EventManagerAwareInterface
{
    /**
     * @var AuthenticationProviderInterface
     */
    protected $authenticationProvider;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param AuthenticationProviderInterface $authenticationProvider
     * @param ServiceManager $serviceManager
     */
    public function __construct(AuthenticationProviderInterface $authenticationProvider, ServiceManager $serviceManager)
    {
        $this->authenticationProvider = $authenticationProvider;
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return AuthenticationService
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
            )
        );
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}