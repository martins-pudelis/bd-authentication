<?php

namespace BdAuthentication\Service;

use BdUser\UserInterface;
use Zend\Crypt\Password\Bcrypt;
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

class PasswordChangeService implements EventManagerAwareInterface
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
     * @param $password
     * @return string
     */
    public function createPassword($password)
    {
        $config = $this->getServiceManager()->get('Config');
        $bcryptOptions = $config['bd_configuration']['bcrypt'];

        $bcrypt = new Bcrypt($bcryptOptions);

        return $bcrypt->create($password);
    }

    /**
     * @param $token
     */
    public function validatePasswords($token)
    {
        $password = $this->getForm()->get('password')->getValue();
        $passwordAgain = $this->getForm()->get('passwordAgain')->getValue();

        $config = $this->getServiceManager()->get('Config');
        $minPasswordLength = $config['bd_configuration']['password-change']['min_password_length'];

        if ($password == $passwordAgain) {
            if (strlen($password) >= $minPasswordLength) {
                $tokens = $this->authenticationProvider
                    ->findAllPasswordRecoveryRequestBy(array('token' => $token));

                $request = array_pop($tokens);
                /** @var PasswordRecoveryRequest $request */

                $passwordHash = $this->createPassword($password);
                $request->setPassword($passwordHash);

                $this->authenticationProvider->storePasswordRecoveryRequest($request);
                $authenticationData = $request->getUser()->getAuthenticationData();

                $authenticationData->setPassword($passwordHash);
                $this->authenticationProvider->storeAuthenticationData($authenticationData);
            } else {
                $message = sprintf('Password should be at least %d characters long', $minPasswordLength);
                $this->getFlashMessenger()->getPluginFlashMessenger()->addErrorMessage($message);
            }
        }
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
     * Set flash messenger
     *
     * @param FlashMessenger $flashMessenger
     */
    public function setFlashMessenger($flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }
}
