<?php
namespace BdAuthentication;

use Application\Module\AbstractModule;

class Module
{
    /*public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }*/

    public function getConfig()
    {
        /*echo '<pre>';
        var_dump(include __DIR__ . '/config/module.config.php');
        die();*/
        return include __DIR__ . '/config/module.config.php';
    }

    protected function getModuleNamespace()
    {
        return __NAMESPACE__;
    }
}