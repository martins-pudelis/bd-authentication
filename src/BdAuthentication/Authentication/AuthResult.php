<?php

namespace BdAuthentication\Authentication;

use Zend\Authentication\Result;
use BdUser\Entity\User;

class AuthResult extends Result
{
    /**
     * @var null
     */
    protected $user = null;

    /**
     * User is locked because of too many wrong authentication attempts
     */
    const USER_LOCKED_FOR_A_WHILE = -16;

    /**
     * User is disabled
     */
    const USER_DISABLED = -17;

    /**
     * @param int $code
     * @param mixed $identity
     * @param array $messages
     */
    public function __construct($code, $identity, array $messages = array())
    {
        $code = (int) $code;

        if ($code > self::SUCCESS) {
            $code = 1;
        }

        $this->code     = $code;
        $this->identity = $identity;
        $this->messages = $messages;
    }

    /**
     * @param null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }
}
