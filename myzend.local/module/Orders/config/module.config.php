<?php

return array(
    // привязка контроллеров модуля
    'controllers' => array(
        'invokables' => array(
            'Orders\Controller\Index' => 'Orders\Controller\IndexController',

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
            'order' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/order[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        //'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Orders\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                /*
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
                */
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
            __DIR__ . '/../view',
        ),
        'strategies' => array( // for ajax
            'ViewJsonStrategy',
        ),
    ),
);