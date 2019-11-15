<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 29.06.2018
 * Time: 11:39
 */

namespace Controller;

use isv\Controller\ControllerBase;
use isv\IS;
use isv\View\ViewBase;
use Models\TriggerModel;

class TriggerController extends ControllerBase
{

    private $model;

    public function init()
    {
        parent::init();
        
        ini_set('memory_limit', '-1');
        $this->model = new TriggerModel();
    }

    /**
     * indexAction method require to declared
     * @return mixed
     */
    function indexAction()
    {
        session_start();
        if(sizeof($this->model->existTable('main_db'))){
            $db = $this->model->all('main_db');
        }
        if(sizeof($this->model->existTable('secondary_db'))){
            $secondary = $this->model->all('secondary_db');
        }
        if ($this->model->countCreatedTrigger()->num_rows){
            $trigger = $this->model->createdTrigger();
        }
        
        return new ViewBase(compact('db','secondary','trigger'));
    }

    /**
     * set db when track change
     */
    public function mainAction(){
        $mainDB = IS::app()->request()->postData();
        $this->model->writeDBInfo($mainDB,'main_db');
        header('Location: /trigger');
        exit();
    }

    public function secondarydbAction(){
        $secondaryDB = IS::app()->request()->postData();
        $this->model->writeSecondDBInfo($secondaryDB,'secondary_db');
        header('Location: /trigger');
        exit();
    }

    /**
     *update second db inf
     */
    public function updateAction(){
        $data = IS::app()->request()->postData();
        $this->model->updateSecondDB($data,'secondary_db');
        header('Location: /trigger');
        exit();
    }

    /**
     *delete second db
     */
    public function deleteAction(){
        $data = IS::app()->request()->postData();
        $this->model->deleteSeconderyDB('secondary_db',$data['id']);
        header('Location: /trigger');
        exit();
    }

    /**
     *Create table for trigger and trigger
     */
    public function starttriggerAction(){
        $data = IS::app()->request()->postData();
        if($data['trigget_start']){
            $tablesInfo = $this->model->getScanerTables('main_db');
            $tables = explode(' ', $tablesInfo[0]['tables']);
            foreach ($tables as $table){
                $fields = $this->model->getScanerField($tablesInfo[0]['prefix'].$table);
                $this->model->createTriggerTable($tablesInfo[0]['prefix'].$table,$fields);
                $this->model->createTrigger($tablesInfo[0]['prefix'].$table,$fields);
            }
            $_SESSION['success'] = 'Таблици и тригеры созданы';
        }
        
        header('Location: /trigger');
        exit();
    }

    public function triggerupdatetableAction(){
        $data = IS::app()->request()->postData();
        if($data['trigget_update']){
            $mainTableInfo = $this->model->getScanerTables('main_db');
            $mainTables = explode(' ', $mainTableInfo[0]['tables']);
            $secondDBInfo = $this->model->getScanerTables('secondary_db');
            
            $this->model->deleteOldProduct();

            foreach ($secondDBInfo as $item){
                $this->model->triggerQuery($mainTableInfo,$mainTables,$item);
            }

            $this->model->deleteRow($mainTableInfo[0]['tables'],$mainTableInfo[0]['prefix']);
            
            
        }

        header('Location: /trigger');
        exit();
    }
    
    public function statictableAction(){
        $data = IS::app()->request()->postData();

        if($data['static_update']){
            $mainTableInfo = $this->model->getScanerTables('main_db');
            $staticTables = explode(' ', $mainTableInfo[0]['static_tables']);
            $secondDBInfo = $this->model->getScanerTables('secondary_db');
            
            
            $this->model->updateStaticTables($staticTables,$mainTableInfo,$secondDBInfo);

        }
        
        header('Location: /trigger');
        exit();
    }
}