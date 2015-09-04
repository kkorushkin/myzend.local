<?php
/*
 * Для загрузки и настройки модуля в Zend Framework 2 существует Менеджер Модуля(ModuleManager) .
 * Он будет искать файл  Module.php в корневом каталоге модуля (module/Collection)
 * в котором должен находиться класс с названием CollectionModule. Это означает,
 * что все классы в модуле будут в пространстве имен с названием модуля(совпадает с именем директории модуля) .
 */
namespace Order;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
//db
use Collection\Model\Collection;
use Collection\Model\CollectionTable;
use Collection\Model\Cart;
use Collection\Model\CartTable;
use Order\Model\Order;
use Order\Model\OrderTable;
//forms
use Collection\Form\SearchForm;
use Collection\Form\SearchFormFilter;
use Order\Form\OrderForm;
use Order\Form\OrderFormFilter;
//view helper

class Module{

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',),
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
    public function getServiceConfig(){
        return array(
            'factories' => array(
                //DB
                'CollectionTable' => function ($sm) {
                    $tableGateway = $sm->get('CollectionTableGateway');
                    $table = new CollectionTable($tableGateway);
                    return $table;
                },
                'CollectionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Collection());
                    return new TableGateway('items', $dbAdapter, null, $resultSetPrototype);
                },
                'CartTable' => function ($sm) {
                    $tableGateway = $sm->get('CartTableGateway');
                    $table = new CartTable($tableGateway);
                    return $table;
                },
                'CartTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cart());
                    return new TableGateway('cart', $dbAdapter, null, $resultSetPrototype);
                },
                'OrderTable' => function ($sm) {
                    $tableGateway = $sm->get('OrderTableGateway');
                    $table = new OrderTable($tableGateway);
                    return $table;
                },
                'OrderTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Order());
                    return new TableGateway('cart', $dbAdapter, null, $resultSetPrototype);
                },
                //Forms
                'SearchForm' => function($sm){
                    $form = new SearchForm();
                    $form->setInputFilter($sm->get('SearchFormFilter'));
                    return $form;
                },
                'OrderForm' => function($sm){
                    $form = new OrderForm();
                    $form->setInputFilter($sm->get('OrderFormFilter'));
                    return $form;
                },
                //Filters
                'SearchFormFilter' => function($sm){
                    return new SearchFormFilter();
                },
                'OrderFormFilter' => function($sm){
                    return new OrderFormFilter();
                },
            ),
        );
    }
}
