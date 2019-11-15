<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Widget;

/**
 * Description of StructureTableWidget
 *
 * @author denis
 */

use isv\IS;
use isv\View\Widget;

class StructureTableWidget extends Widget {
    
    public function main() {
         $mainComp = new \Component\MainComponent();
         $configComp = new \Component\ConfigComponent();
         $fields = $configComp->set('key', 'doc_fields')->get();
         $structure = $configComp->set('key', $this->params['key']) ->get();
         if ($structure)
            ksort($structure);
         return $this ->render('structure_table',[
             'fields' => $fields,
             'structure' => $structure,
         ]);
    }

//put your code here
}
