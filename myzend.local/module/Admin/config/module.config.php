<?php

return array(
    // привязка контроллеров модуля
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\AdminUserManager' => 'Admin\Controller\AdminUserManagerController',
            'Admin\Controller\AdminCollectionManager' => 'Admin\Controller\AdminCollectionManagerController',
            'Admin\Controller\AdminOrdersManager' => 'Admin\Controller\AdminOrdersManagerController',
        ),
    ),
/*
    Маршруты. Последнее действие по конфигурированию модуля — опреде-
    ление маршрута доступа к этому модулю из браузера. В данном случае мы
    определяем маршрут как /currenttime, который указывает на действие index (indexAction)
    в контроллере Index (IndexController) модуля CurrentTime:
*/
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'auth',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // Это маршрут, предлагаемый по умолчанию.
                    //  Его разумно использовать при разработке модуля;
                    // с появлением определенности в отношении маршрутов для модуля, возможно, появится
                    // смысл указать здесь более точные пути.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
            'index' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/index[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                    ),
                ),
            ),
            'user-manager' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/user-manager[/:action][/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'AdminUserManager',
                        'action'        => 'index',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                    ),
                ),
            ),
            'collection-manager' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/collection-manager[/:action[/:id]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'AdminCollectionManager',
                        'action'        => 'index',
                        'constraints' => array(
                            'action'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'      => '[0-9]'
                        ),
                    ),
                ),
            ),
            'orders-manager' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/orders-manager[/:action[/:id]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'AdminOrdersManager',
                        'action'        => 'index',
                        'constraints' => array(
                            'action'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'      => '[0-9]'
                        ),
                    ),
                ),
            ),
        ),
    ),
/*
    Представления. Представления модуля необходимо связать с их местоположениями.
    Обратите внимание на то, что имена представлений записаны в нижнем регистре
    и разделены дефисом (например, обращение к ZendSkeleton будет выглядеть как zend-skeleton):
*/
    'view_manager' => array(
        'template_map' => array(
            //'layout/layout' => __DIR__ . '/../view/admin/layout/layout.phtml',
            //'admin/index' => __DIR__ . '/../view/admin/index/index.phtml',
            //'admin/auth' => __DIR__ . '/../view/admin/auth/auth.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);