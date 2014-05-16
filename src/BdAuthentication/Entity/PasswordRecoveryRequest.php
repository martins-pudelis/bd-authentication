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
     * @ORM\Column(type="string")
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
}
