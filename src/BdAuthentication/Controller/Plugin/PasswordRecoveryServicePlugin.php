<?php

namespace BdAuthentication\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use BdAuthentication\Service\PasswordRecoveryService;

class PasswordRecoveryServicePlugin extends AbstractPlugin
{

    /**
     * @var PasswordRecoveryService
     */
    protected $passwordRecoveryService;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (empty($this->passwordRecoveryService)) {
            $this->passwordRecoveryService = $this->getController()->getServiceLocator()->get('PasswordRecoveryService');
        }

        return call_user_func_array(array($this->passwordRecoveryService, $name), $arguments);
    }
}
