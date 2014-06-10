<?php
namespace BdAuthentication;

return array(
    'controllers' => array(
        'invokables' => array(
            'bd_authentication_login' => 'BdAuthentication\Controller\IndexController',
            'bd_authentication_password_recovery' => 'BdAuthentication\Controller\PasswordRecoveryController',
        ),
    ),
    'forms' => require 'forms.config.php',
    'module_layouts' => array(
        'BdAuthentication' => 'layout/authentication-layout',
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/auth-layout' => __DIR__ . '/../view/layout/authentication-layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'aliases' => array(
            'AuthService' => 'BdAuthentication\Service\AuthService',
            'PasswordRecoveryService' => 'BdAuthentication\Service\PasswordRecoveryService',
            'PasswordChangeService' => 'BdAuthentication\Service\PasswordChangeService',
        ),
        'factories' => array(
            'BdAuthentication\Service\AuthService' => 'BdAuthentication\Service\AuthServiceFactory',
            'BdAuthentication\Service\PasswordRecoveryService' => 'BdAuthentication\Service\PasswordRecoveryServiceFactory',
            'BdAuthentication\Service\PasswordChangeService' => 'BdAuthentication\Service\PasswordChangeServiceFactory',
        )
    ),

    'bd_configuration' => array(
        'bcrypt' => array(
            'salt' => 'sdknsdkarnewq54354tFsdt54ryhgujgjyt87t87t',
            'cost' => '14',
        ),

        'password-recovery' => array(
            'token_expire_time' => 24, //hours
        ),

        'password-change' => array(
            'min_password_length' => 8,
        ),
    ),

    'router' => array(
        'routes' => array(
            'bd_authentication_login' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'bd_authentication_login',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                ),
            ),
            'bd_authentication_recovery' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/recovery',
                    'defaults' => array(
                        'controller' => 'bd_authentication_password_recovery',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),

            'bd_authentication_logout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'bd_authentication_login',
                        'action' => 'logout',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),
            'bd_authentication_password_reset' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/password-reset[/:token]',
                    'constraints' => array(
                        'token' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'bd_authentication_password_recovery',
                        'action' => 'password-reset',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'authService' => 'BdAuthentication\Controller\Plugin\AuthServicePlugin',
            'passwordRecoveryService' => 'BdAuthentication\Controller\Plugin\PasswordRecoveryServicePlugin',
        )
    ),
);