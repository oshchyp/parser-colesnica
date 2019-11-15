<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller;

use Component\ConfigComponent;
use Component\MainComponent;
use Component\ParseComponent;
use isv\Controller\ControllerBase;
use isv\IS;
use isv\View\ViewBase;

/**
 * Description of ParseController
 *
 * @author denis
 */
class ParseController extends ControllerBase {

    private $configComp;
    private $mainComp;
    private $parseComp;

    public function init() {
        $this->configComp = new ConfigComponent();
        $this->mainComp = new MainComponent();
        $this->parseComp = new ParseComponent();
    }

    public function indexAction() {

        return new ViewBase();
    }

    public function productAction() {

        $this->parseComp->set('parse_fields', ['img', 'name','diameter','width','profile']);
        $this->parseComp->set('cat', $this->params('id'));
        $this->parseComp->loadObj(str_replace(' ','+',$this->params('id')));
        $product_info = $this->parseComp->getInfo();
        $this->mainComp->saveProductFile('/files_with_arrays/' . str_replace(' ','_',$this->params('id')), $product_info);
        echo json_encode(true);
        die();
    }

    public function test_imgAction() {
        $this->parseComp->set('parse_fields', ['img']);
        $this->parseComp->set('cat', isset($_GET['cat']) ? $_GET['cat'] : 'tr');
        $this->parseComp->loadObj(isset($_GET['name']) ? $_GET['name'] : 'tr');
        $product_info = $this->parseComp->getInfo();
        return new ViewBase([
            'product_info' => $product_info
                ]
        );
    }

    public function get_json_productsAction() {
        $pr = $this->configComp->set('name', 'products')->get();
        $json_array = [
            'q' => 0,
            'pr' => []
        ];
        if ($pr) {
            foreach ($pr as $k => $v) {
                if ($v && count($v)) {
                    $json_array['q'] += count($v);
                    $json_array['pr'][$k] = $v;
                }
            }
        }

        echo json_encode($json_array);
        die();
    }

    public function parse_priceAction() {
        $url = IS::app()->request()->postData('url');
        if (!$url)
            $this->mainComp->echoJSON(['success' => false, 'msg' => 'Не найдена ссылка']);
        $pr_info = $this->parseComp->set('url', $url)->set('parse_fields', ['products_in_table'])->loadObj()->getInfo();
        $this->mainComp->echoJSON(['success' => $pr_info ? true : false, 'msg' => $pr_info ? 'Успешно' : 'Ошибка']);
    }

    public function parse_price_linksAction() {
        $url = IS::app()->request()->postData('url');
        if (!$url)
            $this->mainComp->echoJSON(['success' => false, 'msg' => 'Не найдена ссылка']);
        $data = [];
        $count = 0;
        $parse = true;
        $page = 1;
        $first_page = '';
        while ($parse) {
            $buff = explode('?',$url);

            if ($page === 1){
                $query = isset($buff[1])?$buff[1]:'PAGEN_1=1&AJAX_PAGE=Y';
            } else{
                if (isset($buff[1])){
                    $query = preg_replace('/PAGEN_1=\d+/m','PAGEN_1=' . $page ,$buff[1]);
                }else{
                    $query = 'PAGEN_1='.$page.'&AJAX_PAGE=Y';
                }
            }

            $url = $buff[0].'?' . $query;

            $html = $this->parseComp->getCURL($url);

            if ($page === 1){
                $first_page = $html;
            }

            if ($first_page === $html && $page !== 1){
                $parse = false;
            }

            if ($parse){
                $pr_info = $this->parseComp->set('url', $url)->set('parse_fields', ['products_in_table'])->loadObj()->getInfo();
                $data[] = $page;
                $count += count($pr_info['products_in_table']);
            }
            $page++;
        }
        $this->mainComp->echoJSON(['success' => true, 'data' => $data, 'msg' => 'ok','count_product' => $count]);
    }

//put your code here
}
