<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller;

use isv\Controller\ControllerBase;
use isv\IS;
use isv\View\ViewBase;

/**
 * Description of DocController
 *
 * @author denis
 */
class DocController extends ControllerBase {

    private $configComp;
    private $mainComp;
    private $xlsComp;
    private $imgComp;

    public function init() {
        $this->configComp = new \Component\ConfigComponent();
        $this->mainComp = new \Component\MainComponent();
        $this->xlsComp = new \Component\XlsComponent();
        $this->imgComp = new \Component\ImgComponent();
    }

    public function indexAction() {
        
    }

    public function uploadAction() {
        
        $cat = $this->configComp->set('key','cat')->get();
        $shabs = $this->configComp->set('key','shabs')->get();
        
        if (IS::app()->request()->postData()){
            $shab = $this->configComp->set('key','doc_in_'.IS::app()->request()->postData('shabs'))->get();
            if (!$shab)
                IS::app()->session()->setFlash('error', 'Не найден шаблон!','/doc/upload');
           
            $fileComp = new \Component\UploadFileComponent();
            $fileComp->save();
            
            if (($sheet = (int)IS::app()->request()->postData('sheet') - 1) < 0)
                $sheet = 0;
           // \isv\Developer\Developer::dump($sheet,1);
            $ignore = IS::app()->request()->postData('ignored') ? explode(',',IS::app()->request()->postData('ignored')) : [];
             
            $this->xlsComp->load([
                'structure' => $shab,
                'sheet' => $sheet,
                'ignore' => $ignore,
            ]);
            if (IS::app()->request()->postData('xml_url')){
                $this->xlsComp->set('file_dir',IS::app()->request()->postData('xml_url'));
                $products = $this->xlsComp->readFileXml();
            } else
               $products = $this->xlsComp->readFile();
            $this -> mainComp ->saveProduct($products,IS::app()->request()->postData('cat'));
           
            IS::app()->session()->setFlash('success', 'Успешно');
            $this->redirect('/doc/upload');
        }
        
        return new ViewBase([
            'cat' => $cat,
            'shabs' => $shabs
        ]);
    }
    
    public function del_roductsAction(){
        $this->configComp->set('name','products')->save([]);
        IS::app()->session()->setFlash('success', 'Успешно');
        $this->redirect('/doc/upload');
    }
    
    public function downloadAction(){
        $products = $this->configComp->set('name','products')->get();
        $img = $this->imgComp ->readDir();
        $q_products=0;
        $q_categories=0;
        $q_img = $img ? count($img) : 0;
        if ($products){
            foreach ($products as $v)
              $q_products+=count($v);
           
            $q_categories = count($products);    
        }
        
        return new ViewBase([
            'q_products' => $q_products,
            'q_categories' => $q_categories,
            'q_img' => $q_img,
        ]);
    }
    
    public function download_xlsAction(){
        $file_name = 'download.xls';
        $this->xlsComp -> set('file_name',$file_name);
        $this->xlsComp -> set('configComp',$this->configComp);
        $this->xlsComp->createFile();
        
        $products = $this->configComp->set('name','products')->get();
        $categories = $this->configComp->set('name','main')->set('key','cat')->get();
        $fields = $this->configComp->set('key','doc_fields')->get();
        
        if ($products){
            $r = 2; $i=1;
            $fields_structure = [];
            foreach ($products as $k=>$v){
                $structure = $this->configComp->set('key','doc_out_'.$k)->get();
                foreach ($structure as $str_key => $str_val){
                    $fields_structure[$str_key] = $str_val;
                }
                $this->xlsComp->set('structure', $structure);
                $this->xlsComp->set('category_key', $k);
                $category_name = isset($categories[$k]) ? $categories[$k] : $k;
                $this->xlsComp ->setRow(['category_name' => $i.'. '.$category_name],$r); $r++;$i++;
                if (count($v)){
                    foreach ($v as $pr_info){
                        $pr_info = $this->mainComp ->convertProduct($pr_info);
                        $this->xlsComp ->setRow($pr_info,$r); $r++;
                    }
                }
            }
        }
        // \isv\Developer\Developer::dump($fields,1);
        $this->xlsComp->set('structure', $fields_structure);
        $this->xlsComp ->setRow($fields,1,false);
        $this->xlsComp->saveXls();
        $fileComp = new \Component\UploadFileComponent();
        $fileComp ->download(ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/xls_upload/'.$file_name);
    }
    
    
    public function download_imgAction(){
        $zip_file = ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'].'/files/zip/product_imgs.zip';
        $zip = new \ZipArchive();
        if (is_file($zip_file))
            unlink($zip_file);
        $zip->open($zip_file, \ZIPARCHIVE::CREATE);
        $imgComp =  new \Component\ImgComponent();
        $files = $imgComp ->readDir();
        if ($files){
            foreach ($files as $v){
                $zip->addFile(ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/images/products/'.$v,$v);
            }
        }
        $zip->close();
        $fileComp = new \Component\UploadFileComponent();
        $fileComp ->download($zip_file);
 
        die();
    }

//put your code here
}
