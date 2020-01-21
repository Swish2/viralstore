<?php

class Marketer{
    private $db = null;

    public function __construct($db){
        $this->db = $db;
    }

    public function registerMarketer($bname,$phone,$email,$ref,$level,$pwd){
        $sql = $this->db->prepare("INSERT INTO `marketers`(`fname`, `email`,`phone`,`img`,`sponsor`,`levelid`,`password`,`date`,`status`) VALUES (?,?,?,?,?,?,?,?,?)");
        $date = date('Y-m-d H:i:s');
        $status ='a';
        $img = '';
        $sql->bind_param('ssssiisss',$bname,$email,$phone,$img,$ref,$level,$pwd,$date,$status);
        if($sql->execute()){
            return true;
        }
    }

    public function updateMarketer($column,$condition){
        $sql = $this->db->prepare("UPDATE marketers SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }else{
            return $this->db->error;
        }
    }

    public function countMarketer($condition  = null){
        $q = "SELECT id FROM marketers ";
        if($condition != null){
            $q .= "where $condition";
        }
        $sql = $this->db->prepare($q);
        $sql->execute();
        $sql->store_result();
            return $sql->num_rows;
    }

    public function readMarketer($column = '*',$condition=null){
        $q = "SELECT $column FROM marketers ";
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

    public function createEarnings($mid){
        $sql = $this->db->prepare("INSERT INTO earnings(`mid`) VALUES(?)");
        $sql->bind_param('i',$mid);
        if($sql->execute()){
            return true;
        }
    }

    public function addEarnings($mid,$amount){
        $sql = $this->db->prepare("UPDATE earnings SET balance = balance +? WHERE mid=?");
        $sql->bind_param('di',$amount,$mid);
        if($sql->execute()){
            return true;
        }
    }

    public function withdrawEarnings($mid,$amount){
        $sql = $this->db->prepare("UPDATE earnings SET balance = balance -? WHERE mid=?");
        $sql->bind_param('di',$amount,$mid);
        if($sql->execute()){
            return true;
        }
    }

    public function updateEarnings($mid, $status){
        $sql = $this->db->prepare("UPDATE earnings SET status = ? WHERE mid=?");
        $sql->bind_param('si',$status,$mid);
        if($sql->execute()){
            return true;
        }
    }

    public function checkEarningsBalance($mid){
        $sql = $this->db->prepare("SELECT balance FROM earnings WHERE mid=?");
        $sql->bind_param('i',$mid);
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }

    public function readLevel($column='*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM levels WHERE $condition");
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }

    public function readSale($mid){
        $sql = $this->db->prepare("SELECT SUM(amount) as total FROM `sale` WHERE mid=? AND marchant_status='a' AND admin_status='i'");
        $sql->bind_param('i',$mid);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    } 

    public function updateBankInfo($bankcode,$bankname,$acctnumber,$acctname,$mid){
        $sql = $this->db->prepare("UPDATE `marketers` SET `bankcode`=?,`bankname`=?,`acctnumber`=?,`acctname`=? WHERE id=?");
        $sql->bind_param('isssi',$bankcode,$bankname,$acctnumber,$acctname,$mid);
        if($sql->execute()){
            return true;
        }
    }

    public function addTransaction($mid,$amount,$type,$status){
        $sql = $this->db->prepare("INSERT INTO transaction(`mid`,`amount`,`transactiontype`,`status`,`date`) VALUES(?,?,?,?,?)");
        $date = date('Y-m-d H:i:s');
        $sql->bind_param('idsss',$mid,$amount,$type,$status,$date);
        if($sql->execute()){
            return true;
        }
    }
    public function readTransaction($column='*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM transaction WHERE $condition");
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }
    
    public function updateTransaction($column,$condition = null){
        $q = "UPDATE transaction SET $column ";
        if($condition != null){
            $q .="WHERE $condition";
        }
        $sql = $this->db->prepare($q);
        if($sql->execute()){
            return true;
        }
    }

    public function countTransaction($condition  = null){
        $q = "SELECT id FROM transaction ";
        if($condition != null){
            $q .= "where $condition";
        }
        $sql = $this->db->prepare($q);
        $sql->execute();
        $sql->store_result();
            return $sql->num_rows;
    }

    public function login($email,$pwd){
        $sql = $this->db->prepare("SELECT id FROM `marketers` WHERE `email`=? AND `password`=?");
        $sql->bind_param('ss',$email,$pwd);
        $sql->execute();
        $sql->store_result();
        return $sql->num_rows;
    }

    public function getId($email,$pwd){
        $sql = $this->db->prepare("SELECT id as mid FROM `marketers` WHERE email=? AND password=?");
        $sql->bind_param('ss',$email,$pwd);
        $sql->execute();
        $sql->bind_result($mid);
        if($sql->fetch()){
            return $mid;
        }
    }
}