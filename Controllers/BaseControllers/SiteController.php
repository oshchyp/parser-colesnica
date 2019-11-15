<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 6/7/17
 * Time: 2:46 PM
 */

namespace Controller\BaseControllers;


use isv\Controller\ControllerBase;
use isv\IS;

abstract class SiteController extends ControllerBase
{
    public function init()
    {
        if (isset($_GET['page']) && is_numeric($_GET['page']))
            IS::app()->set('page', (int)$_GET['page']);
    }
}