<?php

namespace BdAuthentication\Service;

use BdUser\UserInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use BdGeneric\GenericDoctrineProvider;
use BdUser\Service\UserService;
use BdUser\Entity\UserDetail;
use BdUser\Entity\User;
use BdAuthentication\Entity\PasswordRecoveryRequest;
use BdAuthentication\AuthenticationProviderInterface;
use Zend\Form\Form;
use Doctrine\ORM\EntityManager;
use Zend\View\Helper\FlashMessenger;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

class PasswordRecoveryService implements EventManagerAwareInterface
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
     * @var FlashMessenger
     */
    protected $flashMessenger;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var EntityManager
     */
    protected $em;

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

    /**
     * @param \Zend\Form\Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

    /**
     * @return null|FlashMessenger
     */
    public function getFlashMessenger()
    {
        if (!$this->flashMessenger) {
            $this->setFlashMessenger(new FlashMessenger());
        }

        return $this->flashMessenger;
    }

    /**
     * @return string
     */
    public function generateToken()
    {
        return sha1(openssl_random_pseudo_bytes(60) . time());
    }

    /**
     * @return bool
     */
    public function processRequest()
    {
        $userService = $this->getServiceManager()->get('UserService');
        $email = $this->getForm()->get('email')->getValue();

        /* @var $userService UserService */
        $usersDetails = $userService->findUserDetailBy(array('email' => $email));

        if ($usersDetails) {
            $userDetail = array_pop($usersDetails);
            /** @var UserDetail $userDetail */

            $user = $userDetail->getUser();
            $this->storeRecoveryRequest($user);

            return true;
        } else {
            $error = 'User with such email does not exists!';
            $this->getFlashMessenger()->getPluginFlashMessenger()->addErrorMessage($error);

            return false;
        }
    }

    /**
     * @param User $user
     */
    public function storeRecoveryRequest(User $user)
    {
        $authService = $this->getServiceManager()->get('AuthService');
        /** @var AuthService $authService */

        $dateTime = new \DateTime('now');
        $token = $this->generateToken();
        $request = $this->getServiceManager()->get('Request');

        $recoveryRequest = $authService->getNewPasswordRecoveryRequest();
        /** @var PasswordRecoveryRequest $recoveryRequest */

        $recoveryRequest->setUser($user);
        $recoveryRequest->setBrowserSignature($request->getServer('HTTP_USER_AGENT'));
        $recoveryRequest->setToken($token);
        $recoveryRequest->setInsertDate($dateTime->format('Y-m-d H:i:s'));
        $recoveryRequest->setIp($request->getServer('REMOTE_ADDR'));

        $authService->storePasswordRecoveryRequest($recoveryRequest);

        $this->sendMail(
            $user->getUserDetail()->getEmail(),
            $token
        );
    }

    /**
     * @param $to
     * @param $token
     */
    public function sendMail($to, $token)
    {
        $message = new Message();
        $config = $this->getServiceManager()->get('Config');

        $viewRenderer = $this->getServiceManager()->get('ViewRenderer');

        $parameters = array(
            'token' => $token,
        );

        $content = $viewRenderer->render('email/password-recovery', $parameters);

        $message->setBody($content);
        $message->setFrom($config['bd_generic_config']['email'], $config['bd_generic_config']['name']);
        $message->addTo($to, $to);
        $message->setSubject('Password recovery request');

        $transport = new Sendmail();
        $transport->send($message);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isTokenValid($token)
    {
        $tokens = $this->authenticationProvider
            ->findAllPasswordRecoveryRequestBy(array('token' => $token));

        if (count($tokens) > 0) {
            $request = array_pop($tokens);
            /** @var PasswordRecoveryRequest $request */

            $config = $this->getServiceManager()->get('Config');
            $expireHours = $config['bd_configuration']['password-recovery']['token_expire_time'];
            $dateTimeInterval = new \DateInterval(sprintf('PT%dH', $expireHours));

            $today = new \DateTime('now');

            $insertDate = new \DateTime($request->getInsertDate());
            $insertDate->add($dateTimeInterval);

            if ($insertDate > $today) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $token
     * @return array
     */
    public function getRequestByToken($token)
    {
        $request = $this->authenticationProvider->findAllPasswordRecoveryRequestBy(
            array('token' => $token)
        );

        return $request;
    }

    /**
     * Set flash messenger
     *
     * @param FlashMessenger $flashMessenger
     */
    public function setFlashMessenger($flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }
}
