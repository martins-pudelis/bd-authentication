<?php

namespace BdAuthentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/authentication-layout');

        $this->getServiceLocator()->get('AuthService');

        return array(
            'loginForm' => $this->getServiceLocator()->get('bd_login_form')
        );
    }

    public function logoutAction()
    {
    }
}
