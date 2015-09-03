<?php

return array(
    // привязка контроллеров модуля
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Index' => 'Users\Controller\IndexController',
            'Users\Controller\Registration' => 'Users\Controller\RegistrationController',
            'Users\Controller\Login' => 'Users\Controller\LoginController',
            //'Users\Controller\Manager' => 'Users\Controller\ManagerController',
        ),
    ),
/*
    Маршруты. Последнее действие по конфигурированию модуля — опреде-
    ление маршрута доступа к этому модулю из браузера. В данном случае мы
    определяем маршрут как /users, который указывает на действие index (indexAction)
    в контроллере Index (IndexController) модуля Users:
*/
    'router' => array(
        'routes' => array(
            'users' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/users',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Users\Controller\Login',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'registration' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/registration[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Users\Controller\Registration',
                                'action' => 'registration',
                            ),
                        ),
                    ),
                    /*'manager' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/manager[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Users\Controller\Manager',
                                'action' => 'index',
                            ),
                        ),
                    ),*/
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

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view/users',
        )
    )
);



