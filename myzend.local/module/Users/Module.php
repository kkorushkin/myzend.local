<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Users\Model\Users;
use Users\Model\UsersTable;

use Users\Form\LoginForm;
use Users\Form\RegisterForm;
use Users\Form\UserEditForm;
use Users\Form\UserAddForm;

use Users\Form\RegisterFormFilter;
use Users\Form\LoginFormFilter;
use Users\Form\UserEditFormFilter;
use Users\Form\UserAddFormFilter;


use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface{

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

/*    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    } */

    public function getServiceConfig(){
        return array(
            'abstract_factories' => array(),//Определение массива абстрактных классов
            'aliases' => array(),//Определение массива связанных пар «псевдоним/целевое имя»
            'factories' => array(//Определения массива пар «имя службы/класс фабрики».
                // база данных
                'UsersTable' => function($sm){
                   // echo __METHOD__." is reached; test echo in line ".__LINE__;
                    $tableGateway = $sm->get('UsersTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'UsersTableGateway' => function($sm){
                    //echo __METHOD__." is reached; test echo in line ".__LINE__;
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users());
                    return new TableGateway('users', $dbAdapter, null,
                        $resultSetPrototype);
                },
                // Формы
                'LoginForm' => function($sm){
                    $form = new LoginForm();
                    $form->setInputFilter($sm->get('LoginFormFilter'));
                    return $form;
                },
                'RegisterForm' => function($sm){
                    $form = new RegisterForm();
                    $form->setInputFilter($sm->get('RegisterFormFilter'));
                    return $form;
                },
                'UserEditForm' => function($sm){
                    $form = new UserEditForm();
                    $form->setInputFilter($sm->get('UserEditFormFilter'));
                    return $form;
                },
                'UserAddForm' => function($sm){
                    $form = new UserAddForm();
                    $form->setInputFilter($sm->get('UserAddFormFilter'));
                    return $form;
                },
                // Фильтры
                'LoginFormFilter' => function($sm){
                    return new LoginFormFilter();
                },
                'RegisterFormFilter' => function($sm){
                    return new RegisterFormFilter();
                },
                'UserEditFormFilter' => function($sm){
                    return new UserEditFormFilter();
                },
                 'UserAddFormFilter' => function($sm){
                    return new UserAddFormFilter();
                },
            ),
            'invokables' => array(),//Определение массива пар «имя службы/имя класса».
            'services' => array(),//Определение массива пар «имя службы/имя объекта».
            'shared' => array(),//Определение массива пар «имя службы/логическое значение».
        );
    }

}