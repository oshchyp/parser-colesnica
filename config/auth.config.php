<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 30.07.16
 * Time: 16:05
 */
return [
    'user' => [
        'registerLabels' => [
            'username'   => 'Имя пользователя',
            'password'   => 'Пароль',
            'email'      => 'Email',
            'firstName'  => 'Имя',
          
           
           
            'address'    => 'Адерс',
          
            'phone'      => 'Телефон',
            'password_confirm' => 'Подтверждение пароля',
           
        ],
        'fields' => [
            'username',  'email', 'firstName', 'secondName', 'country', 'city',
            'address', 'postalCode', 'phone', 'password' /*'test',*/
        ],
        'requiredFields' => [
            'username', 'password', 'email', 'firstName', 
            'address', 'phone',
        ],
        'uniqueFields' => ['username', 'phone', 'email'],
        'identityBy' => ['username', 'email', 'phone'],
        'successUrl' => '/',
        'defaultRole' => 3,
        'ip' => true,
        'confirmEmail' => true,
        'authTrying' => 5,
        'remember' => 3600,
        'ajaxValidate' => true,

        'roles' => [
            0 => 'Администратор',
            1 => 'Оператор',
            2 => 'Менеджер',
            3 => 'Клиент',
            4 => 'VIP клиент',
            5 => 'Подписчик'
        ],
    ]
];