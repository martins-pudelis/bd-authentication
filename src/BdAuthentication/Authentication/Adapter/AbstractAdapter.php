<?php

namespace BdAuthentication\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BdAuthentication\Authentication\AuthResult;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractAdapter implements AdapterInterface, ServiceManagerAwareInterface
{
    /**
     * @var null
     */
    protected $options = null;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $identity = null;

    /**
     * @var null|ServiceManager
     */
    protected $serviceManager = null;

    /**
     * @var array
     */
    protected $authenticateResultInfo = null;

    /**
     * @param EntityManager $em
     * @param $options
     */
    public function __construct(EntityManager $em, $options)
    {
        $this->setEm($em);
        $this->setOptions($options);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Adapter
     */
    public function setIdentity($value)
    {
        $this->identity = $value;

        return $this;
    }

    /**
     * @param null $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Creates a Zend\Authentication\Result object from the information that
     * has been collected during the authenticate() attempt.
     *
     * @return AuthResult
     */
    protected function authenticateCreateAuthResult()
    {
        return new AuthResult(
            $this->authenticateResultInfo['code'],
            $this->authenticateResultInfo['identity'],
            $this->authenticateResultInfo['messages']
        );
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function authenticateSetup();

    /**
     * @return mixed
     */
    abstract public function authenticate();

    /**
     * @return mixed
     */
    abstract protected function createQuery();

    /**
     * @param array $resultIdentities
     * @return mixed
     */
    abstract protected function authenticateValidateResultSet(array $resultIdentities);

    /**
     * @param $resultIdentity
     * @return mixed
     */
    abstract protected function authenticateValidateResult($resultIdentity);
}