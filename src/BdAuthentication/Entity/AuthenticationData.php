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
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $passwordUntilValidDate;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    protected $lastLoginDate;
}
