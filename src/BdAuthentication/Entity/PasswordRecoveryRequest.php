<?php

namespace BdAuthentication\Entity;

use BdAuthentication\PasswordRecoveryRequestInterface;
use BdGeneric\Db\Entity;
use Doctrine\ORM\Mapping as ORM;
use BdUser\Entity\User as User;

/**
 * @ORM\Entity
 * @ORM\Table(name="bd_password_recovery_request");
 */
class PasswordRecoveryRequest extends Entity implements PasswordRecoveryRequestInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BdUser\Entity\User", inversedBy="passwordRecoveryRequest")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $insertDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $result;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $ip;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $browserSignature;

    /**
     * @param string $browserSignature
     */
    public function setBrowserSignature($browserSignature)
    {
        $this->browserSignature = $browserSignature;
    }

    /**
     * @return string
     */
    public function getBrowserSignature()
    {
        return $this->browserSignature;
    }

    /**
     * @param string $insertDate
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;
    }

    /**
     * @return string
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \BdUser\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \BdUser\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
