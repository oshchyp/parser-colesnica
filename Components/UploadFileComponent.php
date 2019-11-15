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
 * Description of UploadFileComponent
 *
 * @author denis
 */
class UploadFileComponent extends ISVComponent implements ISVComponentInterface {

    private $dir;
    private $key = 'file';
    private $name = 'upload';
    private $exp = '.xls';

    public function init() {
        $this->dir = ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/xls_upload';
    }

    public function set($k, $v) {
        $this->$k = $v;
    }
    
    public function get($k){
        return $this->$k;
    }

    public function load($data = []) {
        if (count($data))
            foreach ($data as $k => $v)
                $this->$k = $v;
    }

    public function save() {
        if (!isset($_FILES[$this->key]) || !trim($_FILES[$this->key]['name']) || !trim($_FILES[$this->key]['tmp_name']))
            return false;
        $this->exp = $this->exp ? $this->exp : $this->getExp($_FILES[$this->key]['name']);
        $save = move_uploaded_file($_FILES[$this->key]['tmp_name'], $this->dir.'/'.$this->name.$this->exp);
        $error = isset($_FILES[$this->key]['error']) ? $_FILES[$this->key]['error'] : 0;
        return $save ? true : $error;
    }

    public function getExp($name = '') {
        $file_expansion = array_pop(explode('.', $name));
        if ($file_expansion == $name)
            $file_expansion = '';
        else
            $file_expansion = '.' . $file_expansion;

        return $file_expansion;
    }
    
    public function download($path,$name = false){
        if (!$name)
            $name = array_pop(explode('/',$path));
       
        header("Content-Disposition: attachment; filename=".$name); 
        header("Content-Type: application/x-force-download; name=\"".$path."\"");
        echo file_get_contents($path);
        die();
    }

}
