<?php

namespace BdAuthentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Http\Request as HttpRequest;
use BdAuthentication\Service\AuthService;
use BdAuthentication\Service\PasswordChangeService;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package BdAuthentication\Controller
 * @method \BdAuthentication\Service\AuthService authService()
 * @method \BdAuthentication\Service\PasswordRecoveryService passwordRecoveryService()
 *
 */
class PasswordRecoveryController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/authentication-layout');

        /** @var Form $passwordRecoveryForm */
        $passwordRecoveryForm = $this->getServiceLocator()->get('bd_password_recovery_form');

        $request = $this->getRequest();

        if ($request instanceof HttpRequest && $request->isPost()) {
            $passwordRecoveryForm->setData($request->getPost());
            $this->passwordRecoveryService()->setForm($passwordRecoveryForm);

            $result = $this->passwordRecoveryService()->processRequest();
            if ($result) {
                $message = 'Request processed successfully. Please check your email for further instructions!';
                $this->flashMessenger()->addSuccessMessage($message);
                $this->redirectToLogin();
            }
        }

        return array(
            'recoveryForm' => $passwordRecoveryForm
        );
    }

    public function passwordResetAction()
    {
        $this->layout('layout/authentication-layout');
        $token = $this->getEvent()->getRouteMatch()->getParam('token', null);
        $passwordResetForm = $this->getServiceLocator()->get('bd_password_reset_password_form');

        if ($token && $this->passwordRecoveryService()->isTokenValid($token)) {
            /** @var Form $passwordResetForm */

            $request = $this->getRequest();

            if ($request instanceof HttpRequest && $request->isPost()) {
                $passwordChangeService = $this->getServiceLocator()->get('PasswordChangeService');
                /** @var PasswordChangeService $passwordChangeService */

                $passwordResetForm->setData($request->getPost());
                $passwordChangeService->setForm($passwordResetForm);
                $passwordChangeService->validatePasswords($token);

                $message = 'Password changed successfully!';
                $this->flashMessenger()->addSuccessMessage($message);
                $this->redirectToLogin();
            }

        } else {
            $message = 'Token invalid or has been expired!';
            $this->flashMessenger()->addErrorMessage($message);
            $this->redirectToLogin();
        }

        return array(
            'resetForm' => $passwordResetForm
        );
    }

    public function redirectToLogin()
    {
        $this->redirect()->toRoute('bd_authentication_login');
    }
}
