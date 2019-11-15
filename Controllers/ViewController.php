<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller;

use isv\Controller\ControllerBase;
use isv\IS;
use isv\View\ViewBase;

/**
 * Description of ViewController
 *
 * @author denis
 */
class ViewController extends ControllerBase {

    private $configComp;
    private $mainComp;
    private $imgComp;

    public function init() {
        $this->configComp = new \Component\ConfigComponent();
        $this->mainComp = new \Component\MainComponent();
        $this->imgComp = new \Component\ImgComponent();
    }

    public function indexAction() {
        $this->redirect('/404');
    }

    public function productsAction() {
        return new ViewBase();
    }

    public function imgAction() {
        return new ViewBase();
    }

    public function delAction() {

        $products = $this->configComp->set('name', 'products')->get();
        $img = $this->imgComp->readDir();
        $q_products = 0;
        $q_categories = 0;
        $q_img = $img ? count($img) : 0;
        if ($products) {
            foreach ($products as $v)
                $q_products+=count($v);

            $q_categories = count($products);
        }

        return new ViewBase([
            'q_products' => $q_products,
            'q_categories' => $q_categories,
            'q_img' => $q_img,
        ]);
    }

    public function del_imgAction() {
        $this->imgComp ->clearDir();
        IS::app()->session()->setFlash('success', 'Успешно');
        $this->redirect('/view/del');
    }

    public function del_prAction() {
     //   $this->imgComp ->set('img_dir',ROOTDIR.'/files_with_arrays') -> clearDir();
        $this->configComp->set('name','products')->save([]);
        IS::app()->session()->setFlash('success', 'Успешно');
        $this->redirect('/view/del');
    }

//put your code here
}
