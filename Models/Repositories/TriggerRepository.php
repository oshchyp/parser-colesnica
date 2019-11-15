<?php

namespace Models\Repositories;


class TriggerRepository
{
    protected $bd;

    protected $prefixs = ['update_','delete_','insert_'];

    protected $triggerAction = [
        'update_' => 'AFTER UPDATE',
        'delete_' => 'BEFORE DELETE',
        'insert_' => 'AFTER INSERT'
    ];

    /**
     *
     * @param $fields
     * @param $table_name
     * @return mixed|string
     */
    public function createTable($fields, $table_name){
        $query = "CREATE TABLE IF NOT EXISTS `{$table_name}` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ";
        foreach ($fields as $k => $field){
            $query .= "{$k} VARCHAR(255), ";
        }
        $query = str_replace(', )',')',$query. ');');
        return $query;
    }

    /**
     * Check exist table
     * @param $table
     * @return string
     */
    public function checkTable($table){
        $sql = "SHOW TABLES LIKE '{$table}';";
        return $sql;
    }

    /**
     * Select all records with all fields
     * @param $link
     * @param $table
     * @return mixed
     */
    public function selectAll($link, $table){
        $query = "SELECT * FROM {$table}";
        $result = $link->query($query);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $result;
    }

    /**
     * Add records
     * @param $data
     * @param $table
     * @return string
     */
    public function writeInDB($data, $table){
        $query = "INSERT INTO `{$table}` (";
        foreach ($data as $k => $val){
            $query .= "`{$k}`, ";
        }
        $query .= ') VALUES (';
        foreach ($data as $k => $val){
            $query .= "'{$val}', ";
        }
        $query = str_replace(', )',')',$query. ');');
        return $query;
    }

    public function updateInfo($data, $table, $id){
        $query = "UPDATE `{$table}` SET ";
        foreach ($data as $k => $val){
            $query .= "`{$k}`='{$val}', ";
        }
        $query .= "WHERE id={$id};";
        $query = str_replace(', WHERE',' WHERE',$query);
        return $query;
    }

    public function delete($table, $id = null){
        if (is_array($id)){
            $sql = "DELETE FROM `{$table}` WHERE IN(";
            foreach ($id as $value){
                $sql = "`id`={$value},";
            }
            $sql .= ");";
            $sql = str_replace(',)',')',$sql);
            return $sql;
        } elseif (isset($id)){
            return "DELETE FROM `{$table}` WHERE `id` = {$id} ;" ;
        }else {
            return "DELETE FROM `{$table}`;" ;
        }
    }

    public function getFieldTable($table){
        return "SHOW COLUMNS FROM `{$table}`;";
    }

    public function getOne($table, $id, $fields = null){
        $sql = "SELECT ";
        if (isset($fields)){
            if(is_array($fields)){
                foreach ($fields as $field){
                    $sql .= " $field, ";
                }
            }
            $sql .= "WHERE `id`={$id};";
            $sql = str_replace(', WHERE',' WHERE', $sql);
            return $sql;
        } else {
            return "SELECT * FROM `{$table}` WHERE `id`={$id}";
        }
    }

    /**
     * Create table fore trigger
     * @param $table
     * @param $tableFieald
     * @param $prefix
     * @return string
     */
    public function creatTableForTrigger($table, $tableFieald, $prefix){
        $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}{$table}` (";
        if($prefix == 'update_'){
            foreach ($tableFieald as $fieald){
                $sql .= !empty($fieald['Field']) ? ' `old_' .$fieald['Field'].'`' : ' ';
                $sql .= !empty($fieald['Type']) ? ' ' . strtoupper($fieald['Type']) : ' ';
                $sql .= ',';
            }
            foreach ($tableFieald as $fieald){
                $sql .= !empty($fieald['Field']) ? ' `new_' .$fieald['Field'].'`' : ' ';
                $sql .= !empty($fieald['Type']) ? ' ' . strtoupper($fieald['Type']) : ' ';
                $sql .= ',';
            }
            $sql .= ');';
            $sql = str_replace(',)',')',$sql);
            return $sql;
        }

        foreach ($tableFieald as $fieald){
            $sql .= !empty($fieald['Field']) ? ' `' .$fieald['Field'].'`' : ' ';
            $sql .= !empty($fieald['Type']) ? ' ' . strtoupper($fieald['Type']) : ' ';
            $sql .= ',';
        }
        $sql .= ');';
        $sql = str_replace(',)',')',$sql);
        return $sql;
    }

    /**
     * Create trigger
     * @param $table
     * @param $tableFieald
     * @param $prefix
     * @param $triggerAction
     * @return mixed|string
     */
    public function creatTrigger($table, $tableFieald, $prefix, $triggerAction){
        $creatTrigger = "CREATE TRIGGER `{$prefix}{$table}_inspect` {$triggerAction} ON `{$table}` FOR EACH ROW BEGIN INSERT INTO `{$prefix}{$table}` SET ";
        foreach ($tableFieald as $fieald){
            if($prefix == 'update_'){
                $creatTrigger .= ' `old_' . $fieald['Field'] . "` = OLD.`" . $fieald['Field'] . '`, ';
                $creatTrigger .= ' `new_' . $fieald['Field'] . "` = NEW.`" . $fieald['Field'] .'`, ';
            } elseif($prefix == 'insert_') {
                $creatTrigger .=  ' `' . $fieald['Field'] . "` = NEW.`" . $fieald['Field'] . '`, ';
            } else {
                $creatTrigger .=  ' `' . $fieald['Field'] . "` = OLD.`" . $fieald['Field'] . '`, ';
            }

        }
        $creatTrigger .= '; END;';
        $creatTrigger = str_replace(', ;',';',$creatTrigger);
        return $creatTrigger;
    }

    /**
     * SQL query for show created trigger
     * @return string
     */
    public function showTrigger(){
        return "SHOW TRIGGERS";
    }

    public function countTrigger(){
        return "SELECT * FROM INFORMATION_SCHEMA.TRIGGERS;";
    }

    /**
     * Delete trigger
     * @param $prefix
     * @param $table
     * @return string
     */
    public function deleteTrigger($prefix, $table){
        return "DROP TRIGGER IF EXISTS `{$prefix}{$table}_inspect`";
    }

    /**
     * Insert sql
     * @param $table
     * @param $insertDatas
     * @return mixed|string
     */
    public function createInsertSQL($table, $insertDatas){
        $insertSql = "INSERT INTO {$table} (";
        foreach ($insertDatas as $k => $v){
            $insertSql .= "`{$k}`, ";
        }
        $insertSql .= ') VALUES (';

        foreach ($insertDatas as $k => $v){
            $insertSql .= " '{$v}', ";
        }
        $insertSql .= ');';
        $insertSql = str_replace(', )',')',$insertSql);
        return $insertSql;
    }

    /**
     * Create Insert sql
     * @param $table
     * @param $deleteDatas
     * @return mixed|string
     */
    public function creatDeleteSql($table, $deleteDatas){
        $deleteSql = "DELETE FROM {$table} WHERE ";
        foreach ($deleteDatas as $k => $v){
            if(!empty($v)){
                if($k == 'product_id' || $k == 'field_id' || $k == 'image_id' || $k == 'id' || $k == 'product_id'){
                    $deleteSql .= "`{$k}` = {$v} AND ";
                    break;
                }
                if(preg_match('/^[0-9.]+$/i', $v)){
                    $deleteSql .= "`{$k}` = {$v} AND ";
                } else {
                    $deleteSql .= "`{$k}` = '{$v}' AND ";
                }
            }
        }
        $deleteSql .= ';';
        $deleteSql = str_replace('AND ;',';',$deleteSql);
        return $deleteSql;
    }

    public function createUpdateSql($table,$updateDatas){
        $updateSql = '';
        $updateSql .= "UPDATE `{$table}` SET ";
        foreach ($updateDatas as $k => $v) {
            if (preg_match('/^(new_)/', $k) !== 0) {
                if($k == 'new_product_id' || $k == 'new_field_id' || $k == 'new_image_id' || $k == 'new_id' || $k == 'new_product_id'){
                    continue;
                } else {
                    if(preg_match('/^[0-9.]+$/i', $v)){
                        $updateSql .= "`" . preg_replace ('/^new_/', '', $k) . "` = {$v}, ";
                    } else {
                        $updateSql .= "`" . preg_replace ('/^new_/', '', $k) . "` = '{$v}', ";
                    } 
                }

            }
        }
        $updateSql .= 'WHERE ';
        foreach ($updateDatas as $k => $v) {
            if ($k === 'id' || $k === 'old_product_id' || $k == 'old_field_id' || $k == 'old_image_id' || $k == 'old_product_id') {
                if (preg_match('/^(old_)/', $k) !== 0 && !empty($v)) {
                    if(preg_match('/^[0-9.]+$/i', $v)){
                        $updateSql .= "`" . preg_replace ('/^old_/', '', $k) . "` = {$v}, ";
                    } else {
                        $updateSql .= "`" . preg_replace ('/^old_/', '', $k) . "` = '{$v}', ";
                    }
                    break;
                }
            } else {
                if (preg_match('/^(old_)/', $k) !== 0 && !empty($v)) {
                    if(preg_match('/^[0-9.]+$/i', $v)){
                        $updateSql .= "`" . preg_replace ('/^old_/', '', $k) . "` = {$v}, ";
                    } else {
                        $updateSql .= "`" . preg_replace ('/^old_/', '', $k) . "` = '{$v}', ";
                    }
                }
            }
        }
        $updateSql .= ';';
        $updateSql = str_replace(', WHERE', ' WHERE', $updateSql);
        $updateSql = str_replace(', ;', ';', $updateSql);

        return $updateSql;
    }
    
    public function insert($data,$table){
        
        $sql .= "INSERT INTO `{$table}` (";
        foreach($data as $key => $val){
            $sql .= "`{$key}`,";
        }
        $sql .= ') VALUES (';
        foreach($data as $key => $val){
            $sql .= preg_match('/^[\d]+$/',$val) ? "{$val},":'"'.$val.'",';
        }
        $sql .= ');';
        
        $sql = str_replace(',)',')', $sql);
        return $sql;
    }

}