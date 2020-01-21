<?php

class User{
    private  $db = null;

    public function __construct($db){
        $this->db = $db;
    }

    public function readUser($column = '*',$condition=null){
        $q = "SELECT $column FROM user ";
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


    public function addUser($uid,$fullName,$email,$img,$type,$status){
        $sql = $this->db->prepare("INSERT INTO user (`uid`,`fname`,`email`,`img`,`type`,`date`,`status`)
        VALUES(?,?,?,?,?,?,?)");
            $date = date('Y-m-d H:i:s');
        $sql->bind_param('sssssss',$uid,$fullName,$email,$img,$type,$date,$status);

        if($sql->execute()){
            return true;
        }
    }

    public function countUser($uid  = null){
        $q = "SELECT id FROM user ";
        if($uid != null){
            $q .= "where uid = $uid";
        }
        $sql = $this->db->prepare($q);
        $sql->execute();
        $sql->store_result();
        
            return $sql->num_rows;
       
    }

    public function updateUser($column,$condition){
        $sql = $this->db->prepare("UPDATE user SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }else{
            return $this->db->error;
        }
    }


}