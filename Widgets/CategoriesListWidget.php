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
 * Description of CategoriesListWidget
 *
 * @author denis
 */
class CategoriesListWidget extends Widget {
    
    public function main() {
       
         $configComp = new \Component\ConfigComponent();
         $configComp->set('key', $this->params['key']);
         $cat = $configComp -> get();
         $url = $this->params['url'];
         return $this->render('categories_list',[
             'cat' => $cat,
             'url' => $url
         ]);
        
    }

//put your code here
}
