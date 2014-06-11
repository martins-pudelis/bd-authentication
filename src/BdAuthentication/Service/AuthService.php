<?php

namespace BdAuthentication\Service;

use BdAuthentication\Authentication\Adapter\SqlAdapter;
use BdAuthentication\Authentication\AuthResult;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use BdGeneric\GenericDoctrineProvider;
use BdAuthentication\AuthenticationProviderInterface;
use Zend\Form\Form;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService as ZendAuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\AdapterInterface;
use BdUser\Entity\User;
use BdUser\Service\UserService;
use Zend\Http\Request;
use Zend\View\Helper\FlashMessenger;
use BdAuthentication\Exception\RuntimeException;
use BdUser\UserInterface;
use BdAuthentication\PasswordRecoveryRequestInterface;

class AuthService extends ZendAuthenticationService implements EventManagerAwareInterface
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
     * Authenticates against the supplied adapter
     *
     * @param  AdapterInterface $adapter
     * @return AuthResult
     * @throws RuntimeException
     */
    public function authenticate(AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$adapter = $this->getAdapter()) {
                throw new RuntimeException('An adapter must be set or passed prior to calling authenticate()');
            }
        }

        /** @var AuthResult $result */
        $result = $adapter->authenticate();

        /**
         * ZF-7546 - prevent multiple successive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }

        if ($result->isValid()) {
            $this->getStorage()->write($result->getUser()->getId());
        }

        return $result;
    }

    public function doAuthenticate()
    {
        $config = $this->getServiceManager()->get('Config');
        $this->clearMessages();

        if ($this->getForm()->isValid()) {
            $adapter = new SqlAdapter(
                $this->getEntityManager(),
                $config
            );

            $adapter->setIdentity($this->getForm()->get('username')->getValue())
                ->setCredential($this->getForm()->get('password')->getValue());

            $this->processResult($this->authenticate($adapter));
        } else {
            $error = 'Username or password not specified!';
            $this->getFlashMessenger()->getPluginFlashMessenger()->addErrorMessage($error);
        }
    }

    /**
     *
     */
    public function clearMessages()
    {
        $this->getFlashMessenger()->getPluginFlashMessenger()->clearMessages();
    }

    public function processUser()
    {

    }

    /**
     * @param Result $authResult
     */
    public function processResult(Result $authResult)
    {
        //Logging request
        $this->logRequestForHistory(
            $authResult->getCode(),
            $authResult->getUser()
        );

        if ($authResult->getCode() === AuthResult::SUCCESS) {
            $this->processUser();
        } else {
            $error = 'Username or password incorrect, please try again!';
            $this->getFlashMessenger()->getPluginFlashMessenger()->addErrorMessage($error);
        }
    }

    /**
     * @return User
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return null;
        }

        $userId = $storage->read();
        $userService = $this->getServiceManager()->get('UserService');

        /* @var $userService UserService */
        $user = $userService->findUser($userId);

        return $user;
    }

    /**
     * @param $event
     * @param User $user
     */
    public function logRequestForHistory($event, User $user = null)
    {
        $authenticationHistory = $this->authenticationProvider->getNewAuthenticationHistory();

        $request = $this->getServiceManager()->get('Request');
        $dateTime = new \DateTime('now');
        $authenticationHistory->setUser($user);
        $authenticationHistory->setResult($event);
        $authenticationHistory->setIp($request->getServer('REMOTE_ADDR'));
        $authenticationHistory->setBrowserSignature($request->getServer('HTTP_USER_AGENT'));
        $authenticationHistory->setInsertDate($dateTime->format('Y-m-d H:i:s'));

        $this->authenticationProvider->storeAuthenticationHistory($authenticationHistory);
    }

    /**
     * @param $authenticationData
     */
    public function storeAuthenticationData($authenticationData)
    {
        $this->authenticationProvider->storeAuthenticationData($authenticationData);
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
     * Set flash messenger
     *
     * @param FlashMessenger $flashMessenger
     */
    public function setFlashMessenger($flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * @return \BdAuthentication\PasswordRecoveryRequestInterface
     */
    public function getNewPasswordRecoveryRequest()
    {
        return $this->authenticationProvider->getNewPasswordRecoveryRequest();
    }

    /**
     * @param PasswordRecoveryRequestInterface $recoveryRequest
     * @return mixed
     */
    public function storePasswordRecoveryRequest(PasswordRecoveryRequestInterface $recoveryRequest)
    {
        return $this->authenticationProvider->storePasswordRecoveryRequest(
            $recoveryRequest
        );
    }

    /**
     * @return \BdAuthentication\Entity\AuthenticationData
     */
    public function getNewAuthenticationData()
    {
        return $this->authenticationProvider->getNewAuthenticationData();
    }
}
