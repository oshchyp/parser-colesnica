<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.08.16
 * Time: 16:49
 */

namespace Widget;


use isv\IS;
use isv\View\Widget;

class BreadcrumbsWidget extends Widget
{
    public function main()
    {
        return $this->render('breadcrumbs', [
            'breadcrumbs' => IS::app()->breadcrumbs()->get(),
        ]);
    }
}