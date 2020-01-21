<?php

class Mlm{
private $db = null;

public function __construct($db){
    $this->db = $db;
}


public function checkLevel($lid){
    $sql = $this->db->prepare("SELECT maxamount FROM levels WHERE id=? AND status='a'");
    $sql->bind_param('i',$lid);
    if($sql->execute()){
        return true;
    }
}

public function readLevel($column = '*',$condition=null){
    $q = "SELECT $column FROM levels ";
    if($condition !=null){
        $q .="WHERE $condition";
    }
    $sql = $this->db->prepare($q);
    $arr = array();
    if($sql->execute()){
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()){
            $arr[] = (object) $row;
        }
        return $arr;
    }
}


public function getChildren($parent){
$sql = $this->db->prepare("SELECT img,fname,id FROM `marketers` WHERE ref=?");
$sql->bind_param('i',$parent);
    $arr = array();
    if($sql->execute()){
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()){
            $arr[] = (object) $row;
        }
        return $arr;
    }
}

public function countChildren($parent){
    $sql = $this->db->prepare("SELECT `id` FROM `marketers` WHERE ref=?");
   $sql->bind_param('i',$parent);
    $sql->execute();
    $sql->store_result();
        return $sql->num_rows;
}



}