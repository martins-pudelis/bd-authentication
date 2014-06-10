<?php

namespace BdAuthentication\Entity;

use BdAuthentication\AuthenticationHistoryInterface;
use Doctrine\ORM\Mapping as ORM;
use BdGeneric\Db\Entity;
use BdUser\Entity\User as User;

/**
 * @ORM\Entity
 * @ORM\Table(name="bd_authentication_history");
 */
class AuthenticationHistory extends Entity implements AuthenticationHistoryInterface
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
     * @ORM\ManyToOne(targetEntity="BdUser\Entity\User", inversedBy="authenticationHistory")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $insertDate;

    /**
     * @ORM\Column(type="string")
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
