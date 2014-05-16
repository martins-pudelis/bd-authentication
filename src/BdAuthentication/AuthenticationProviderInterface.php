<?php
namespace BdAuthentication;

/**
 * Class AuthenticationProviderInterface
 */
interface AuthenticationProviderInterface
{
    /**
     * @param AuthenticationDataInterface $authenticationData
     * @return void
     */
    public function storeAuthenticationData(AuthenticationDataInterface $authenticationData);

    /**
     * @param AuthenticationHistoryInterface $authenticationHistory
     * @return void
     */
    public function storeAuthenticationHistory(AuthenticationHistoryInterface $authenticationHistory);

    /**
     * @param PasswordRecoveryRequestInterface $passwordRecoveryRequest
     * @return mixed
     */
    public function storePasswordRecoveryRequest(PasswordRecoveryRequestInterface $passwordRecoveryRequest);

    /**
     * @param AuthenticationDataInterface $authenticationData
     * @return mixed
     */
    public function removeAuthenticationData(AuthenticationDataInterface $authenticationData);

    /**
     * @param AuthenticationHistoryInterface $authenticationHistory
     * @return mixed
     */
    public function removeAuthenticationHistory(AuthenticationHistoryInterface $authenticationHistory);

    /**
     * @param PasswordRecoveryRequestInterface $passwordRecoveryRequest
     * @return mixed
     */
    public function removePasswordRecoveryRequest(PasswordRecoveryRequestInterface $passwordRecoveryRequest);

    /**
     * @return AuthenticationDataInterface
     */
    public function getNewAuthenticationData();

    /**
     * @return PasswordRecoveryRequestInterface
     */
    public function getNewPasswordRecoveryRequest();

    /**
     * @return AuthenticationHistoryInterface
     */
    public function getNewAuthenticationHistory();

    /**
     * @return array
     */
    public function findAllAuthenticationData();

    /**
     * @return array
     */
    public function findAllPasswordRecoveryRequests();

    /**
     * @return array
     */
    public function findAllAuthenticationHistory();

    /**
     * @param array $criteria
     * @return array
     */
    public function findAuthenticationDataBy(array $criteria);

    /**
     * @param array $criteria
     * @return array
     */
    public function findAuthenticationHistoryBy(array $criteria);

    /**
     * @param array $criteria
     * @return array
     */
    public function findAllPasswordRecoveryRequestBy(array $criteria);

    /**
     * @param $id
     * @return AuthenticationDataInterface
     */
    public function findAuthenticationData($id);

    /**
     * @param $id
     * @return AuthenticationHistoryInterface
     */
    public function findAuthenticationHistory($id);

    /**
     * @param $id
     * @return PasswordRecoveryRequestInterface
     */
    public function findPasswordRecoveryRequest($id);
}
