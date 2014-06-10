<?php

namespace BdAuthentication;

use Doctrine\ORM\EntityManager;
use BdGeneric\GenericDoctrineProvider;

/**
 *
 */
class DoctrineAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \BdGeneric\GenericDoctrineProvider
     */
    protected $authenticationDataProvider;

    /**
     * @var \BdGeneric\GenericDoctrineProvider
     */
    protected $authenticationHistoryProvider;

    /**
     * @var \BdGeneric\GenericDoctrineProvider
     */
    protected $passwordRecoveryProvider;

    /**
     * @param EntityManager $entityManager
     * @param $authenticationDataEntityClass
     * @param $authenticationHistoryClass
     * @param $passwordRecoveryRequestClass
     */
    public function __construct(
        EntityManager $entityManager,
        $authenticationDataEntityClass,
        $authenticationHistoryClass,
        $passwordRecoveryRequestClass
    ) {

        $this->entityManager = $entityManager;
        $this->authenticationDataProvider = new GenericDoctrineProvider($entityManager, $authenticationDataEntityClass);
        $this->authenticationHistoryProvider = new GenericDoctrineProvider($entityManager, $authenticationHistoryClass);
        $this->passwordRecoveryProvider = new GenericDoctrineProvider($entityManager, $passwordRecoveryRequestClass);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param AuthenticationDataInterface $authenticationData
     * @return void
     */
    public function storeAuthenticationData(AuthenticationDataInterface $authenticationData)
    {
        $this->authenticationDataProvider->store($authenticationData);
    }

    /**
     * @param AuthenticationHistoryInterface $authenticationHistory
     * @return void
     */
    public function storeAuthenticationHistory(AuthenticationHistoryInterface $authenticationHistory)
    {
        $this->authenticationHistoryProvider->store($authenticationHistory);
    }

    /**
     * @param PasswordRecoveryRequestInterface $passwordRecoveryRequest
     * @return mixed
     */
    public function storePasswordRecoveryRequest(PasswordRecoveryRequestInterface $passwordRecoveryRequest)
    {
        $this->passwordRecoveryProvider->store($passwordRecoveryRequest);
    }

    /**
     * @param AuthenticationDataInterface $authenticationData
     * @return mixed
     */
    public function removeAuthenticationData(AuthenticationDataInterface $authenticationData)
    {
        $this->authenticationDataProvider->remove($authenticationData);
    }

    /**
     * @param AuthenticationHistoryInterface $authenticationHistory
     * @return mixed
     */
    public function removeAuthenticationHistory(AuthenticationHistoryInterface $authenticationHistory)
    {
        $this->authenticationHistoryProvider->remove($authenticationHistory);
    }

    /**
     * @param PasswordRecoveryRequestInterface $passwordRecoveryRequest
     * @return mixed
     */
    public function removePasswordRecoveryRequest(PasswordRecoveryRequestInterface $passwordRecoveryRequest)
    {
        $this->passwordRecoveryProvider->remove($passwordRecoveryRequest);
    }

    /**
     * @return AuthenticationDataInterface
     */
    public function getNewAuthenticationData()
    {
        return $this->authenticationDataProvider->getNew();
    }

    /**
     * @return PasswordRecoveryRequestInterface
     */
    public function getNewPasswordRecoveryRequest()
    {
        return $this->passwordRecoveryProvider->getNew();
    }

    /**
     * @return AuthenticationHistoryInterface
     */
    public function getNewAuthenticationHistory()
    {
        return $this->authenticationHistoryProvider->getNew();
    }

    /**
     * @return array
     */
    public function findAllAuthenticationData()
    {
        return $this->authenticationDataProvider->findAll();
    }

    /**
     * @return array
     */
    public function findAllPasswordRecoveryRequests()
    {
        return $this->passwordRecoveryProvider->findAll();
    }

    /**
     * @return array
     */
    public function findAllAuthenticationHistory()
    {
        return $this->authenticationHistoryProvider->findAll();
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findAuthenticationDataBy(array $criteria)
    {
        return $this->authenticationDataProvider->findBy($criteria);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findAuthenticationHistoryBy(array $criteria)
    {
        return $this->authenticationHistoryProvider->findBy($criteria);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findAllPasswordRecoveryRequestBy(array $criteria)
    {
        return $this->passwordRecoveryProvider->findBy($criteria);
    }

    /**
     * @param $id
     * @return AuthenticationDataInterface
     */
    public function findAuthenticationData($id)
    {
        return $this->authenticationDataProvider->find($id);
    }

    /**
     * @param $id
     * @return AuthenticationHistoryInterface
     */
    public function findAuthenticationHistory($id)
    {
        return $this->authenticationHistoryProvider->find($id);
    }

    /**
     * @param $id
     * @return PasswordRecoveryRequestInterface
     */
    public function findPasswordRecoveryRequest($id)
    {
        return $this->passwordRecoveryProvider->find($id);
    }
}
