<?php

namespace BdAuthentication\Entity;

use BdAuthentication\AuthenticationDataInterface;
use Doctrine\ORM\Mapping as ORM;
use BdGeneric\Db\Entity;
use BdUser\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="bd_authentication_data");
 */
class AuthenticationData extends Entity implements AuthenticationDataInterface
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
     * @ORM\OneToOne(targetEntity="BdUser\Entity\User", mappedBy="authenticationData")
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
    protected $passwordUntilValidDate;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @var string
     */
    protected $lastLoginDate;

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

    /**
     * @param string $passwordUntilValidDate
     */
    public function setPasswordUntilValidDate($passwordUntilValidDate)
    {
        $this->passwordUntilValidDate = $passwordUntilValidDate;
    }

    /**
     * @return string
     */
    public function getPasswordUntilValidDate()
    {
        return $this->passwordUntilValidDate;
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
     * @param string $lastLoginDate
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
    }

    /**
     * @return string
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }
}
