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
 * Description of MainComponent
 *
 * @author denis
 */
class MainComponent extends ISVComponent implements ISVComponentInterface {

    public function init() {
        
    }
    
   
    public function arrayFormateKeyVal($key=[],$value=[]){
        $result = [];
        if (is_array($key) && count($key) && is_array($value) && count($value)){
            $i=1;
            foreach ($key as $k=>$v){
                $result[trim((string)$v) ? (string)$v : $i] = isset($value[$k]) ? $value[$k] : false;
                $i++;
            }
        }
        return $result;
    }
    
    public function saveProduct($data=[],$category){
         $configComp = new \Component\ConfigComponent();
         $product_save = $configComp->load(['name'=>'products','key'=>$category])->get();
         if ($data)
             foreach ($data as $k=>$v){
                $product_save[$k] = $v;
             }
             $configComp -> save($product_save);
    }
    
    public function varExport($path='',$array=[]){
        $str = '<?php'.PHP_EOL.'return '.var_export($array,true).';';
        return file_put_contents(ROOTDIR.$path, $str);
    }
    
    public function convertProduct($pr=[]){
        $pr_parse_info_dir = ROOTDIR.'/files_with_arrays';
        if (isset($pr['article']) && is_file($pr_parse_info_dir.'/'.$pr['article'].'.php')){
           $pr_parse_info = include $pr_parse_info_dir.'/'.$pr['article'].'.php';
           if (is_array($pr_parse_info) && count($pr_parse_info)){
               foreach ($pr_parse_info as $k=>$v){
                   if ($v && trim($v))
                      $pr[$k] = $v;
               }
           }
        }
        
        return $pr;
    }
    
    public function isAuth(){
        $curlObj = new \Component\CurlComponent();
        $curlObj->set('url', 'http://b2b.euro-diski.ru/auth.php')->curlInit();
        $curlObj->setCookieFile('/' . IS::app()->getConfig('config')['publicDir'] . '/files/cookie.txt');
        $html = $curlObj->getHtml();
        $curlObj->close();
        return strstr($html,'"result":1') ? true : false;
    }
    
    public function saveProductFile($path='',$array=[]){
        $save_array = is_file($path.'.php') ? include $path.'.php' : [];
        if (is_array($array) && count($array))
            foreach ($array as $k=>$v)
                $save_array[$k] = $v;
        $this->varExport($path.'.php',$save_array);
    }
    
    public function echoJSON($array=[]){
        echo json_encode($array);
        die();
    }

}
