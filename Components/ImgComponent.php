<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Component;

use isv\Component\ISVComponent;
use isv\Component\ISVComponentInterface;
use isv\IS;

/**
 * Description of ImgComponent
 *
 * @author denis
 */
class ImgComponent extends ISVComponent implements ISVComponentInterface {

    private $img_dir;
    private $img_name;
    private $obj;
   
    

    public function init() {
        require_once ROOTDIR . '/vendor/imgresize/imgresize.php';
        $this->obj = new \imgresize();
        $this->img_dir = ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images/products';
    }

    public function set($k, $v) {
        $this->$k = $v;
        return $this;
    }

    public function get($k) {
        return $this->$k;
    }

    public function load($data = []) {
        if (count($data))
            foreach ($data as $k => $v)
                $this->$k = $v;
        return $this;
    }

   
//    public function createImgFloat() {
//
//        $this -> obj -> load($this->img_dir . '/' . $this->img_name);
//        $objImg->watermark($this->float, false, (float) $this->img_settings['right'], (float) $this->img_settings['bottom']);
//        $objImg->save($this->new_dir . '/' . $this->img_name, exif_imagetype($this->img_dir . '/' . $this->img_name));
//        return $this->new_dir . '/' . $this->img_name;
//    }
    
    public function readDir(){
        if (!is_dir($this->img_dir))
            return false;
        $scan_dir = scandir($this->img_dir);
        unset($scan_dir[0]); unset($scan_dir[1]);
        
        return count($scan_dir) ? $scan_dir : false;
    }
    
    public function clearDir(){
        if (!$dirr_cont = $this -> readDir())
            return false;
        foreach ($dirr_cont as $v){
            unlink($this->img_dir.'/'.$v);
        }
        return true;
        
    }

//put your code here
}
