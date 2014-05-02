<?php

return array(
    'bd_login_form' => array(
        'type' => 'Zend\Form\Form',
        'hydrator' => new \Zend\Stdlib\Hydrator\ClassMethods(false),
        'elements' => array(
            array(
                'spec' => array(
                    'name' => 'username',
                    'type' => 'Zend\Form\Element\Text',
                    'attributes' => array(
                        'required' => 'required',
                        'placeholder' => 'Username'
                    ),
                )
            ),
            array(
                'spec' =>
                    array(
                        'name' => 'password',
                        'type' => 'Zend\Form\Element\Password',
                        'attributes' => array(
                            'placeholder' => 'Password',
                            'required' => 'required',
                        ),
                    )
            ),
        ),
        'input_filter' => array(
            'username' => array(
                'required' => true,
            ),
            'password' => array(
                'required' => true,
            ),
        ),
    ),
);