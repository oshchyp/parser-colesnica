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
 * Description of CategoriesEditWidget
 *
 * @author denis
 */
class CategoriesEditWidget extends Widget {
    
    public function main() {
        
         $configComp = new \Component\ConfigComponent();
         $mainComp = new \Component\MainComponent();
         $configComp->set('key', $this->params['key']);

        

        $cat = $configComp->get();
        return $this->render('categories_edit',[
            'cat' => $cat
        ]);
    }

//put your code here
}
