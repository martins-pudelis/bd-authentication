<?php

namespace BdAuthentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Http\Request as HttpRequest;

/**
 * Class IndexController
 * @package BdAuthentication\Controller
 * @method \BdAuthentication\Service\AuthService authService()
 *
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/authentication-layout');

        /** @var Form $loginForm */
        $loginForm = $this->getServiceLocator()->get('bd_login_form');

        $request = $this->getRequest();

        if ($request instanceof HttpRequest && $request->isPost()) {
            $loginForm->setData($request->getPost());
            $this->authService()->setForm($loginForm);
            $this->authService()->doAuthenticate();

            if ($this->authService()->hasIdentity()) {
                $this->authService()->clearMessages();

                $this->redirect()->toRoute('bd_home');
            }
        }

        return array(
            'loginForm' => $loginForm
        );
    }

    public function logoutAction()
    {
        $this->authService()->clearIdentity();
        $this->redirect()->toRoute('bd_authentication_login');
    }
}
