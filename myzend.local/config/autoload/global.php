<?php

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=estore;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'invokables' => array( // TODO: This was add customed to make $this->identity() work on all views
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
        ),
        'factories' => array(
            'ZendDbAdapterAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    //
    'satl' => '123xcv098poi765rfv',
);
