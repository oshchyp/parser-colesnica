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
 * Description of ImgWidget
 *
 * @author denis
 */
class ImgWidget extends Widget {
    
    public function main() {
        $imgObj = new \Component\ImgComponent();
        $imgs = $imgObj->readDir();
        return $this->render('img',[
            'imgs' => $imgs
        ]);
    }

//put your code here
}
