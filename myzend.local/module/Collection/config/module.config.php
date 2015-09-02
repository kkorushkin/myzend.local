<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Collection\Controller\Collection' => 'Collection\Controller\CollectionController',
            'Collection\Controller\Cart' => 'Collection\Controller\CartController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'collection' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/collection[/:action][/:id]',// это маршрут и для ajax-запросов !!!
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Collection\Controller\Collection',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
            'cart' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cart[/:action]',// это маршрут и для ajax-запросов !!!
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Collection\Controller\Cart',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            //'layout/layout' => __DIR__ . '/../view/collection/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array( // for ajax
            'ViewJsonStrategy',
        ),
    ),
    // custom helpers
    'view_helpers' => array(
        'invokables' => array(
            'collectionSearchForm' => 'Collection\Helper\CollectionLayoutViewHelperSearchFrom',
            'collectionCartIndicator' => 'Collection\Helper\CollectionLayoutViewHelperCartIndicator',
            'collectionItemCount' => 'Collection\Helper\CollectionViewHelperItemCount',
            'collectionAuth' => 'Collection\Helper\CollectionViewHelperAuth',
        )
    )
    //
);
