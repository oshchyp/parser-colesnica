<?php
namespace Models;

use Exception;
use Models\Repositories\TriggerRepository;
use mysqli;

class TriggerModel extends TriggerRepository
{

    /**
     * TriggerModel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->bd = new mysqli('127.0.0.1','babask37_koles','TEvnL8lr','babask37_koles');
        if($this->bd->connect_error){
            throw new Exception($this->bd->connect_error,500);
        }
    }

    public function writeDBInfo($data,$table){
        $this->bd->query($this->createTable($data,$table)) or die($this->bd->error);
        if(empty($this->selectAll($this->bd,$table))){
            $this->bd->query($this->writeInDB($data,$table)) or die($this->bd->error);
        } else {
            $this->bd->query($this->updateInfo($data,$table,$this->selectAll($this->bd,$table)[0]['id'])) or die($this->bd->error);
        }
    }

    public function writeSecondDBInfo($data,$table){
        $this->bd->query($this->createTable($data,$table)) or die($this->bd->error);
        $this->bd->query($this->writeInDB($data,$table)) or die($this->bd->error);
    }

    public function updateSecondDB($data, $table){
        $id = $data['id'];
        unset($data['id']);
        $this->bd->query( $this->updateInfo($data, $table, $id)) or die($this->bd->error);
    }

    public function all($table){
        return $this->selectAll($this->bd,$table);
    }

    public function deleteSeconderyDB($table,$id){
        $this->bd->query($this->delete($table,$id)) or die($this->bd->error);
    }

    public function getScanerTables($table){
        return $data = $this->selectAll($this->bd, $table);
    }

    public function getScanerField($table){
        $field = $this->bd->query($this->getFieldTable($table)) or die($this->bd->error);
        $field = mysqli_fetch_all($field, MYSQLI_ASSOC);
        return $field;
    }

    public function createTriggerTable($table,$tableFieald)
    {
        foreach ($this->prefixs as $prefix){
            $sql = $this->creatTableForTrigger($table,$tableFieald,$prefix);
            $this->bd->query($sql) or die($this->bd->error);
        }
    }

    public function createTrigger($table, $field){
        foreach ($this->prefixs as $prefix){
            $this->bd->query($this->deleteTrigger($prefix,$table));
            $this->bd->query($this->creatTrigger($table, $field,$prefix,$this->triggerAction[$prefix])) or die($this->bd->error);
        }
    }

    public function createdTrigger(){
        $trigger = $this->bd->query($this->showTrigger())->fetch_all(MYSQLI_ASSOC) or die($this->bd->error);
        return $trigger;
    }

    public function countCreatedTrigger(){
        $trigger = $this->bd->query($this->countTrigger()) or die($this->bd->error);
        return $trigger;
    }

    public function triggerQuery($mainDB,$mainTables,$secondDB){
        foreach ($mainTables as $mainTable){
            foreach ($this->prefixs as $prefix){
               $data =  $this->selectAll($this->bd, "{$prefix}{$mainDB[0]['prefix']}{$mainTable}");
               if(!empty($data)){
                   if($prefix == 'insert_'){
                       foreach ($data as $iteam){
                           $secondDBObjInsert = new mysqli($secondDB['host'],$secondDB['user'],$secondDB['password'],$secondDB['dbname']);
                           $secondDBObjInsert->query($this->createInsertSQL($secondDB['prefix'].$mainTable,$iteam));
                           
                           /*if(!empty($secondDBObjInsert->error)){
                               $_SESSION['error_mass'] .= $secondDBObjInsert->error . "<br>";
                           }*/
                           $secondDBObjInsert->close();
                       }
                   }
                   if ($prefix == 'update_'){
                       foreach ($data as $iteam){
                           $secondDBObjUpdate = new mysqli($secondDB['host'],$secondDB['user'],$secondDB['password'],$secondDB['dbname']);
                           $secondDBObjUpdate->query($this->createUpdateSql($secondDB['prefix'].$mainTable,$iteam));
                           /*if(!empty($secondDBObjUpdate->error)){
                               $_SESSION['error_mass'] .= $secondDBObjUpdate->error . "<br>";
                           }*/
                           $secondDBObjUpdate->close();
                       }
                   }
                   if($prefix == 'delete_'){
                       foreach ($data as $iteam){
                           $secondDBObjDelete = new mysqli($secondDB['host'],$secondDB['user'],$secondDB['password'],$secondDB['dbname']);
                           $secondDBObjDelete->query($this->creatDeleteSql($secondDB['prefix'].$mainTable,$iteam));
                           
                           /*if(!empty($secondDBObjDelete->error)){
                               $_SESSION['error_mass'] .= $secondDBObjDelete->error . "<br>";
                           }*/
                           $secondDBObjDelete->close();
                       }
                   }
               }
            }
        }
    }

    public function deleteRow($table,$prefix){
        $tables = explode(' ',$table);
        foreach ($tables as $tab){
            foreach ($this->prefixs as $v){
                $this->bd->query($this->delete($v.$prefix.$tab));
            }
        }
    }
    
    public function existTable($table){
        return $this->bd->query($this->checkTable($table))->fetch_all(MYSQLI_ASSOC);
    }
    
    public function updateStaticTables($staticTables,$mainTableInfo,$secondDBInfo){
        foreach($staticTables as $table){
            $data = $this->selectAll($this->bd,$mainTableInfo[0]['prefix'].$table);
            foreach($secondDBInfo as $iteam){
                $secondDBObject = new mysqli($iteam['host'],$iteam['user'],$iteam['password'],$iteam['dbname']);
                
                $secondDBObject->query("TRUNCATE `" . $iteam['prefix'].$table . "`; ");
                
                foreach($data as $val){
                    $sql = $this->insert($val,$iteam['prefix'].$table);

                    $secondDBObject->query($sql);
                    if(!empty($secondDBObject->error)){
                        $_SESSION['error_mass'] .= $secondDBObject->error . "<br>";
                    }
                }
                
                $secondDBObject->close();
            }
        }
    }
    
    public function deleteOldProduct(){
        $this->bd->query("DELETE FROM `y3erp_jshopping_products` WHERE `date_modify` <  NOW() - INTERVAL 3 DAY;");
    }
}