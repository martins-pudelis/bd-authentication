<?php

namespace BdAuthentication\Authentication\Adapter;

use BdAuthentication\Authentication;
use Zend\ServiceManager\ServiceManager;
use BdUser\Entity\User;
use BdAuthentication\Exception;
use Zend\Crypt\Password\Bcrypt;
use BdAuthentication\Authentication\AuthResult;

class SqlAdapter extends AbstractAdapter
{
    /**
     * @var string
     */
    protected $credential = null;

    /**
     * @param  string $credential
     * @return AbstractAdapter
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * authenticateSetup() - This method abstracts the steps involved with
     * making sure that this adapter was indeed setup properly with all
     * required pieces of information.
     *
     * @throws Exception\RuntimeException in the event that setup was not done properly
     * @return bool
     */
    protected function authenticateSetup()
    {
        $exception = null;

        if ($this->identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with DbTable.';
        } elseif ($this->credential === null) {
            $exception = 'A credential value was not provided prior to authentication with DbTable.';
        }

        if (null !== $exception) {
            throw new Exception\RuntimeException($exception);
        }

        $this->authenticateResultInfo = array(
            'code'     => AuthResult::FAILURE,
            'identity' => $this->identity,
            'messages' => array()
        );

        return true;
    }

    /**
     * @throws Exception\RuntimeException if answering the authentication query is impossible
     * @return AuthResult
     */
    public function authenticate()
    {
        $this->authenticateSetup();

        $query = $this->createQuery();
        $query->execute();

        $resultIdentities = $query->getResult();
        $authResult = $this->authenticateValidateResultSet($resultIdentities);

        if ($authResult instanceof AuthResult) {
            return $authResult;
        }

        // At this point, ambiguity is already done. Loop, check and break on success.
        foreach ($resultIdentities as $identity) {
            $authResult = $this->authenticateValidateResult($identity);
            if ($authResult->isValid()) {
                break;
            }
        }

        $authResult->setUser(array_pop($resultIdentities));

        return $authResult;
    }

    /**
     * @return \Doctrine\ORM\Query|mixed
     * @throws \BdAuthentication\Exception\RuntimeException
     */
    protected function createQuery()
    {
        $exceptionMessage = null;

        if (empty($this->identity)) {
            $exceptionMessage = 'A value for the identity was not provided prior to authentication with adapter.';
        } elseif (empty($this->credential)) {
            $exceptionMessage = 'A credential value was not provided prior to authentication with adapter.';
        }

        if ($exceptionMessage) {
            throw new Exception\RuntimeException($exceptionMessage);
        }

        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(User::CN(), 'u')
            ->where('u.username = :username');

        /* @var $query \Doctrine\ORM\Query */
        $query = $queryBuilder->getQuery();
        $query->setParameter('username', $this->identity);

        return $query;
    }

    /**
     * authenticateValidateResultSet() - This method attempts to make
     * certain that only one record was returned in the result set
     *
     * @param  array $resultIdentities
     * @return boolean|AuthResult
     */
    protected function authenticateValidateResultSet(array $resultIdentities)
    {
        if (count($resultIdentities) < 1) {
            $this->authenticateResultInfo['code'] = AuthResult::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticateResultInfo['messages'][] = 'A record with the supplied identity could not be found.';

            return $this->authenticateCreateAuthResult();
        } elseif (count($resultIdentities) > 1) {
            $this->authenticateResultInfo['code'] = AuthResult::FAILURE_IDENTITY_AMBIGUOUS;
            $this->authenticateResultInfo['messages'][] = 'More than one record matches the supplied identity.';

            return $this->authenticateCreateAuthResult();
        }

        return true;
    }

    /**
     * authenticateValidateResult() - This method attempts to validate that
     * the record in the resultset is indeed a record that matched the
     * identity provided to this adapter.
     *
     * @param  User $resultIdentity
     * @return AuthResult
     */
    protected function authenticateValidateResult($resultIdentity)
    {
        if (!$resultIdentity) {
            $this->authenticateResultInfo['code'] = AuthResult::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticateResultInfo['messages'][] = 'Identity not found.';
        } else {
            $options = $this->getOptions();

            $bcrypt = new Bcrypt($options['bd_configuration']['bcrypt']);

            $password = $resultIdentity->getAuthenticationData()->getPassword();

            if ($bcrypt->verify($this->getCredential(), $password)) {
                $this->authenticateResultInfo['code'] = AuthResult::SUCCESS;
                $this->authenticateResultInfo['messages'][] = 'Authentication successful.';

            } else {
                $this->authenticateResultInfo['code'] = AuthResult::FAILURE_CREDENTIAL_INVALID;
                $this->authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
            }
        }

        return $this->authenticateCreateAuthResult();
    }
}