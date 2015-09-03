<?php

return array(
    'modules' => array( // массив modules содержит список включенных модулей.
        'Application',
        'Collection',
        'Users',
        'Admin',
        'Orders',
    ),
    'module_listener_options' => array( // содержит маски путей к конфигурационным файлам модулей.
        'config_glob_paths'    => array( // Регулярное выражение 'config/autoload/{,*.}{global,local}.php' означает,
            'config/autoload/{,*.}{global,local}.php', // что будут загружены все файлы вида module_name.{global или local}.php, global.php, local.php из директории config/autoload.
        ),
        'module_paths' => array( // module_paths подсказывает где искать подключаемые модули, по умолчанию:
            './module',          // здесь будут находиться модули разработанные нами для нашего проекта
            './vendor',          // в директориях vendor — внешние зависимости. http://habrahabr.ru/post/192522/
        ),
    ),
);