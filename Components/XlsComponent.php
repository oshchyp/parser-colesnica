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
 * Description of XlsComponent
 *
 * @author denis
 */
class XlsComponent extends ISVComponent implements ISVComponentInterface {

    private $file;
    private $structure = [];
    private $sheet = 0;
    private $ignore = [1];
    private $uniq = 'article';
    private $file_dir;
    private $file_name = 'upload.xls';
    private $xls;
    private $sheetObj;

    public function init() {
        $this->file_dir = ROOTDIR . '/' . IS::app()->getConfig('config')['publicDir'] . '/files/xls_upload';
     //   $this->file = $this->file_dir.'/'.$this->file_name;
    }

    public function set($k, $v) {
        $this->$k = $v;
    }

    public function load($data = []) {
        if (count($data))
            foreach ($data as $k => $v)
                $this->$k = $v;
    }

    public function get($k) {
        return $this->$k;
    }

    public function getUniqCeil() {
        if ($this->structure)
            foreach ($this->structure as $k => $v)
                if ($v == $this->uniq)
                    return $k;
        return false;
    }

    public function readFile() {
        if (!is_file($this->file_dir.'/'.$this->file_name) || !$this->structure)
            return false;
        $xls = \PHPExcel_IOFactory::load($this->file_dir.'/'.$this->file_name);
        $xls->setActiveSheetIndex($this->sheet);
        $sheet = $xls->getActiveSheet();
        $result = [];
        $uniq = $this->getUniqCeil();
        foreach ($sheet->getRowIterator() as $k => $row) {
            if (!is_array($this->ignore) || !in_array($k, $this->ignore)) {
                $key = $uniq ? $sheet->getCell($uniq.$k)->getValue() : $k;
                foreach ($this->structure as $k1 => $v1) {
                    $result[$key][$v1] = $sheet->getCell($k1.$k) -> getValue();
                }
            }
        }

        return $result;
    }
 // tires   
//    <root>
//    <tires>
//    <cae>2504900</cae>
//    <name>265/70R16 112T Ice Zero (шип.)</name>
//            <width>265.00</width>
//            <height>70.00</height>
//            <diameter>R16.00</diameter>
//            <diameter_out>R0.00</diameter_out>
//            <load_index>112</load_index>
//            <speed_index>T</speed_index>
//            <model>Ice Zero</model>
//            <brand>Pirelli</brand>
//            <season>Зимняя</season>
//            <is_studded>шип</is_studded>
//            <tiretype>Легковая</tiretype>
//            <runflat/><reinforced/>
//            </tires>
//    
//    
//    
//    
//    <root>
//    <rims>
//    <cae>WHS146937</cae>
//    <name>8x18/5x150 ET56 D110,1 TY71 SF (пш)</name>
//            <width>8.00</width>
//            <diameter>18.00</diameter>
//            <bolts_count>5</bolts_count>
//            <bolts_spacing>150.00</bolts_spacing>
//            <bolts_spacing2>0.00</bolts_spacing2>
//            <et>56.00</et>
//            <dia>110.10</dia>
//            <model>TY71</model>
//            <brand>Replay</brand>
//            <color>SF</color>
//            <rim_type>0</rim_type>
    
    public function readFileXml(){
        if (!$this->structure)
            return false;
        $curl = new \Component\CurlComponent();
        $curl -> set('url',$this->file_dir)->curlInit();
        $movies = new \SimpleXMLElement($curl ->getHtml());
        $result = [];
        $uniq = $this->getUniqCeil();
        $i=0;
        foreach ($movies->tires ? $movies->tires : $movies->rims as $k=>$v){
           $key = $v->$uniq ? trim((string)$v->$uniq) : $i;
           foreach ($this->structure as $k1=>$v1){
               $result[$key][$v1] = $v->$k1 ? trim((string)$v->$k1) : '';
           }
           $i++;
        }
        return $result;
    }


    public function createFile(){
        $this->xls = new \PHPExcel();
        $this->xls->setActiveSheetIndex(0);
        $this->sheetObj = $this->xls->getActiveSheet();
        $this->sheetObj->setTitle('sheet 1');
    }
    
    public function setRow($data=[],$i=0,$convert=true){
        if ($data && count($data) && $this -> structure && count($this -> structure)){
            foreach ($this -> structure as $k=>$v){
                if (isset($data[$v])){
                    $methodName = 'convert'.ucfirst($v);
                    if (method_exists($this,$methodName) && $convert)
                            $data[$v] = $this -> $methodName($data,$v);
                    $this->sheetObj->setCellValue($k.$i, $data[$v]);
                }
            }
        }
    }
    
    public function saveXls(){
        $objWriter = new \PHPExcel_Writer_Excel5($this->xls);
        $objWriter->save($this->file_dir.'/'.$this->file_name);
    }
    
    
    
    ///////convert data methods/////
    
    
    public function convertDiameter($d,$k){
        $d = isset($d[$k]) ? $d[$k] : false;
        $int = str_replace(['ZR','R','C','—'],'',$d);
        $res = str_replace($int,(float)$int,$d);
        return $res;
    }
    
    public function convertName($d, $k) {
//                if ($this -> category_key == 'disks')
//        \isv\Developer\Developer::dump($d,1);
        $convertName = $this->configComp->set('key', 'convert_name')->get();
        $name = isset($convertName[$this->category_key]) ? $convertName[$this->category_key] : '';
        foreach ($d as $k1 => $v) {
            $methodName = 'convert' . ucfirst($k1);
            if (method_exists($this, $methodName) && $k !== $k1)
                $v = $this->$methodName($d, $k1);
            $name = str_replace('{' . $k1 . '}', $v, $name);
        }
        return $name;
    }

    public function convertWidth($d,$k){
        return isset($d[$k]) ? (float)str_replace(',','.',$d[$k]) : 0;
    }
    
    public function convertRun_flat($d,$k){
        $r = isset($d[$k]) ? trim($d[$k]) : '';
        $r = $r == 'Да' ? 'RunFlat' : '';
        return $r;
    }
    
    public function convertDia($d, $k) {
//        if ($d[$k] == 67.09999999999999) {
//            
//        }
        $d = isset($d[$k]) ? trim(str_replace(',', '.', $d[$k])) : 0;
        $d = number_format((float) $d, 2, '.', '');
        if (substr($d, -1, 1) == '0')
            $d = substr($d, 0, -1);
        return $d;
    }

//put your code here
}
