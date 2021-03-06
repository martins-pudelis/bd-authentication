<?php

namespace BdAuthentication\Service;

use Doctrine\ORM\EntityManager;
use BdAuthentication\DoctrineAuthenticationProvider;
use BdAuthentication\Entity\AuthenticationData;
use BdAuthentication\Entity\AuthenticationHistory;
use BdAuthentication\Entity\PasswordRecoveryRequest;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use BdAuthentication\Exception;

class AuthServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceManager
     * @throws Exception\RuntimeException
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {

        /* @var $entityManager EntityManager */
        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        /* @var $doctrineAuthenticationProvider DoctrineAuthenticationProvider */
        $doctrineAuthenticationProvider = new DoctrineAuthenticationProvider(
            $entityManager,
            AuthenticationData::CN(),
            AuthenticationHistory::CN(),
            PasswordRecoveryRequest::CN()
        );

        $authenticationService = new AuthService($doctrineAuthenticationProvider, $serviceManager);

        return $authenticationService;
    }
}
