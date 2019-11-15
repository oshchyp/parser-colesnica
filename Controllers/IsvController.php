<?php
namespace Controller;
use isv\Controller\ControllerBase;
use isv\Developer\Generator;
use isv\View\ViewBase;
use isv\IS;
/**
 * Class IsvController
 * @package Controller
 */
class IsvController extends ControllerBase
{
    public function init()
    {
        IS::app()->set('templateName', 'default');
        IS::app()->set('layout', 'default');
        $ip = $_SERVER['REMOTE_ADDR'];
        $config = IS::app()->getConfig('config');
        $allowIp = $config['development'];
        if( !in_array($ip, $allowIp) )
        {
            exit('<strong>Access denied for you IP: </strong><code>'.$ip.'</code>');
        }
    }

    public function indexAction()
    {
        IS::app()->set('title', 'Developer part');
        return new ViewBase();
    }

    public function crudAction()
    {
        return new ViewBase();
    }

    public function processAction()
    {
        $tableName = IS::app()->request()->postData('table');
        $generator = new Generator();
        if($generator->modelGenerator($tableName, IS::app()->request()->postData('model')))
            $this->redirect('/isv');
    }
}