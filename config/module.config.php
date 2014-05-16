<?php
namespace BdAuthentication;

return array(
    'controllers' => array(
        'invokables' => array(
            'bd_authentication_login' => 'BdAuthentication\Controller\IndexController',
        ),
    ),
    'forms' => require 'forms.config.php',
    'module_layouts' => array(
        'BdAuthentication' => 'layout/authentication-layout',
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/skeleton-layout' => __DIR__ . '/../view/layout/skeleton-layout.phtml',
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
        ),
        'factories' => array(
            'BdAuthentication\Service\AuthService' => 'BdAuthentication\Service\AuthServiceFactory',
        )
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
     /*       'bd_authentication_recovery' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/recovery',
                    'defaults' => array(
                        'controller' => 'bd_authentication_recovery',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),

            'bd_authentication_change_password' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/change-password',
                    'defaults' => array(
                        'controller' => 'bd_authentication_change_password',
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
            'bd_authentication_activate' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/activate[/:action][/:token]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'token' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'bd_authentication_activate',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),*/
        ),
    ),
);