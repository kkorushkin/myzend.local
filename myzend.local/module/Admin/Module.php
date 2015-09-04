<?php

namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

use Admin\Form\LoginForm;
use Admin\Form\LoginFormFilter;
use Admin\Form\UserAddForm;
use Admin\Form\UserAddFormFilter;
use Admin\Form\AdminCollectionManagerForm;
use Admin\Form\AdminCollectionManagerFormFilter;

use Admin\Model\AdminUsers;
use Admin\Model\AdminUsersTable;
use Admin\Model\AdminCollection;
use Admin\Model\AdminCollectionTable;

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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,),
            ),
        );
    }

    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }
    /*
     * ModuleManager автоматически вызывает методы getAutoloaderConfig() и getConfig().
     */
    /*public function getServiceConfig(){
        return array(
            'factories' => array(
                'Collection\Model\CollectionTable' => function ($sm) {
                    $tableGateway = $sm->get('Collection\TableGateway');
                    $table = new CollectionTable($tableGateway);
                    return $table;
                },
                'Collection\TableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Collection());
                    return new TableGateway('items', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }*/

    public function getServiceConfig(){
        return array(
            'abstract_factories' => array(),//Определение массива абстрактных классов
            'aliases' => array(),//Определение массива связанных пар «псевдоним/целевое имя»
            'factories' => array(//Определения массива пар «имя службы/класс фабрики».
                // база данных
                'AdminUsersTable' => function($sm){
                    // echo __METHOD__." is reached; test echo in line ".__LINE__;
                    $tableGateway = $sm->get('AdminUsersTableGateway');
                    $table = new AdminUsersTable($tableGateway);
                    return $table;
                },
                'AdminUsersTableGateway' => function($sm){
                    //echo __METHOD__." is reached; test echo in line ".__LINE__;
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new AdminUsers());
                    return new TableGateway('users', $dbAdapter, null,
                        $resultSetPrototype);
                },
                'AdminCollectionTable' => function ($sm) {
                    $tableGateway = $sm->get('AdminCollectionTableGateway');
                    $table = new AdminCollectionTable($tableGateway);
                    return $table;
                },
                'AdminCollectionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new AdminCollection());
                    return new TableGateway('items', $dbAdapter, null, $resultSetPrototype);
                },
                // Формы
                'LoginForm' => function($sm){
                    $form = new LoginForm();
                    $form->setInputFilter($sm->get('LoginFormFilter'));
                    return $form;
                },
                'AdminCollectionManagerForm' => function($sm) {
                    $form = new AdminCollectionManagerForm();
                    $form->setInputFilter($sm->get('AdminCollectionManagerFormFilter'));
                    return $form;

                },
                /*
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
                */
                'UserAddForm' => function($sm){
                    $form = new UserAddForm();
                    $form->setInputFilter($sm->get('UserAddFormFilter'));
                    return $form;
                },
                // Фильтры
                'LoginFormFilter' => function($sm){
                    return new LoginFormFilter();
                },
                'AdminCollectionManagerFormFilter' => function($sm){
                    return new AdminCollectionManagerFormFilter();
                },
                /*
                 'RegisterFormFilter' => function($sm){
                    return new RegisterFormFilter();
                },
                'UserEditFormFilter' => function($sm){
                    return new UserEditFormFilter();
                },
                */
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