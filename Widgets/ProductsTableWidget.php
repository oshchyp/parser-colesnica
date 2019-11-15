<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Widget;

use isv\IS;
use isv\View\Widget;
/**
 * Description of ProductsTableWidget
 *
 * @author denis
 */
class ProductsTableWidget extends Widget {
    
    public function main() {
        $configComp = new \Component\ConfigComponent();
        $main_comp = new \Component\MainComponent();
        $products = $configComp -> set('name','products')->get();
     //   \isv\Developer\Developer::dump($products);
        $cat = $configComp -> load(['name'=>'main','key'=>'cat'])->get();
        $fields = $configComp -> load(['key'=>'doc_fields'])->get();
        return $this->render('products_table',[
            'products' => $products,
            'cat' => $cat,
            'fields' => $fields,
            'configComp' => $configComp,
            'view_products' => isset($this->params['view_products']) ? true  :false,
            'main_comp' => $main_comp
        ]);
        
    }

//put your code here
}
