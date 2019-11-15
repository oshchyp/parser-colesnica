<?php
/**
 * The main application configuration file
 */
return [
        'config' => [
            'viewPath' => realpath('../').'/views',
            'layout' => 'layout',
            'template' => 'default',
            'adminTemplate' => 'admin',
            'viewFilesExtension' => '.phtml',
            'host' => $_SERVER['HTTP_HOST'],
            'emailDir' => realpath('../').'/views/default/_email',
            'publicDir' => 'public_html',
            'development' => [
                '127.0.0.1',
                '141.101.17.56',
                '139.59.134.88'
            ],
            'sessionPath' => false,
            'admin' => 1,
        ],
];
