<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 04.02.16
 * Time: 19:53
 */
return [
    'router' => [
        /********************* ADMIN ROUTES ********************/
   
        'admin/{action}' => 'Admin\IndexController',
        /********************* USER ROUTES ********************/
        'isv/{action}/{id}/{name}'   => 'IsvController',
        'settings/{action}/{id}'           => 'SettingsController',
        'parse/{action}/{id}/{id1}'           => 'ParseController',
       
       
    ]
];