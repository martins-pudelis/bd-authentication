<?php

namespace BdAuthentication\Validator;

use Zend\Validator\AbstractValidator;

class PasswordStrength extends AbstractValidator
{

    const PWD_LENGTH = 'length';
    const PWD_RETYPE = 'retype';

    /**
     * @var array with messages template
     */
    protected $messageTemplates = array(
        self::PWD_LENGTH => "Password should be at least %must% characters long, but is %is%",
        self::PWD_RETYPE => "Passwords should match",
    );

    /**
     * Checks does password complies to password policy
     *
     * @param mixed $value
     * @param mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $result = null;

        //@todo should take this value from config
        if (strlen($value) >= 8) {
            $result = $this->passwordsMatch($context);
        } else {
            $this->addError(self::PWD_LENGTH, array(8, strlen($value)));
            $result = false;
        }

        return $result;
    }

    /**
     * Checks does passwords match
     *
     * @param $context
     * @return bool
     */
    public function passwordsMatch($context)
    {
        if (isset($context['password']) && isset($context['passwordAgain'])) {
            if ($context['password'] !== $context['passwordAgain']) {
                $this->error(
                    self::PWD_RETYPE
                );

                return false;
            }
        }

        return true;
    }

    /**
     * @param $key
     * @param array $values
     */
    public function addError($key, $values = array())
    {
        $error = $this->messageTemplates[$key];

        $this->setMessage(str_replace(array('%must%', '%is%'), $values, $error));
        $this->error($key, $error);
    }
}
