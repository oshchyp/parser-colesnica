<?php
namespace Controller\Errors;
use isv\Controller\ControllerBase;
/**
 * Master-cms
 * Class IndexController
 * @package Controller
 */
Class ErrorController extends ControllerBase
{
    public function indexAction()
    {
        echo '404 Страница не найдена! Вернуться на <a href="/">главную</a>';
    }
    public function servererorAction()
    {
        echo '<h2><strong>sorry, technical works in the server now. Please try load this '
        . 'page letter or contact admin</strong></h2>';
    }

    public function badrequestAction()
    {
        echo '<h2>400 Bad Request</h2>';
    }
}