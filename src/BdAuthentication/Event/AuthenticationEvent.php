<?php

namespace BdAuthentication\Event;

use Zend\Mvc\MvcEvent as MvcEvent;
use BdAuthentication\Service\AuthService;
use Zend\Console\Request as ConsoleRequest;

class AuthenticationEvent
{
    /**
     * @var array
     */
    protected $exceptionalControllers = array(
        'bd_authentication_login',
        'bd_authentication_password_recovery',
    );

    /**
     * @var AuthService
     */
    protected $authService = null;

    /**
     * @param MvcEvent $event
     */
    public function preDispatch(MvcEvent $event)
    {
        $controller = $event->getRouteMatch()->getParam('controller');

        $instance = (!$event->getRequest() instanceof ConsoleRequest);

        if ($instance) {
            if (!in_array($controller, $this->getExceptionalControllers())) {
                if (!$this->getAuthService()->hasIdentity()) {

                    $url = $event->getRouter()->assemble(
                        array(),
                        array('name' => 'bd_authentication_login')
                    );

                    $response = $event->getResponse();
                    $response->getHeaders()->addHeaders(array(array('Location' => $url)));
                    $response->setStatusCode(302);
                    $response->sendHeaders();
                }
            }
        }
    }

    /**
     * @param AuthService $authenticationService
     */
    public function setAuthService(AuthService $authenticationService)
    {
        $this->authService = $authenticationService;
    }

    /**
     * @return AuthService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param array $exceptionalControllers
     */
    public function setExceptionalControllers($exceptionalControllers)
    {
        $this->exceptionalControllers = $exceptionalControllers;
    }

    /**
     * @return array
     */
    public function getExceptionalControllers()
    {
        return $this->exceptionalControllers;
    }
}
