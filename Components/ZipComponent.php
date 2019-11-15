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
 * Description of ZipComponent
 *
 * @author denis
 */
class ZipComponent extends ISVComponent implements ISVComponentInterface {
   
    
    private $dir;
    private $zipObj;
  

    public function init() {
        $this -> dir =  ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images/products';
        $this -> zipObj = new \ZipArchive();
     }
    
    public function set($k, $v) {
        $this->$k = $v;
        return $this;
    }

    public function load($data = []) {
        if (count($data))
            foreach ($data as $k => $v)
                $this->$k = $v;
        return $this;
    }

    public function get($k) {
        return $this->$k;
    }
    
    public function createFile($zip_file){
        $this -> zipObj -> open($zip_file, ZIPARCHIVE::CREATE);
    }
    
    public function addFile($file){
         $zip->addFile($images_dir.'/'.$file);
    }













//put your code here
}
