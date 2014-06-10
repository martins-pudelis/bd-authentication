<?php

namespace BdAuthentication\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use BdAuthentication\Service\AuthService;

class AuthServicePlugin extends AbstractPlugin
{

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (empty($this->authService)) {
            $this->authService = $this->getController()->getServiceLocator()->get('AuthService');
        }

        return call_user_func_array(array($this->authService, $name), $arguments);
    }
}
