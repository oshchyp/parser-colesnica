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
 * Description of ConfigComponent
 *
 * @author denis
 */
class ConfigComponent extends ISVComponent implements ISVComponentInterface {
    
    private $name='main';
    
    private $key=false;
    
    private $structure = false;

    const CONSTANT = ROOTDIR.'/config';
    
    public function init() {
        
    }
    
    public function set($k,$v){
        $this -> $k = $v;
        return $this;
    }


    public function load($data=[]){
        if (count($data))
            foreach ($data as $k=>$v)
                $this -> $k = $v;
        return $this;
    }
    
    public function getConfArray(){
        return [$this->name => IS::app()->getConfig($this->name) ? IS::app()->getConfig($this->name) : []];
    }


    public function get(){
       $conf = IS::app()->getConfig($this->name);
       if ($this->key && $conf)
           $conf = isset($conf[$this->key]) ? $conf[$this->key] : [];
       
       if ($this->structure){
           foreach ($this->structure as $v){
               if (!isset($conf[$v]))
                   $conf[$v] = '';
           }
       }
       
       return $conf ? $conf : [];
    }
    
    public function save($data=[]){
      
        $conf_info = $this -> getConfArray();
        if ($this->key)
            $conf_info[$this->name][$this->key] = $data;
        else
            $conf_info[$this->name] = $data;
        //\isv\Developer\Developer::dump($data,1);
        $data_str = '<?php'.PHP_EOL.'return '.var_export($conf_info,true).';';
        file_put_contents(self::CONSTANT.'/'.$this->name.'.php', $data_str);
    }
    
   
//put your code here
}
