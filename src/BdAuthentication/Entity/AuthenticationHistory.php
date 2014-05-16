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
}
