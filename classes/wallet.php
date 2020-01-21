<?php
class Wallet{
    private $db = null;

    public function __construct($db){
        $this->db = $db;
    }

    public function updatePin($id,$pin){
        $sql = $this->db->prepare("UPDATE wallet SET pin=? WHERE id=?");
        $sql->bind_param('si',$pin,$id);
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function updateWallet($column,$condition){
        $sql = $this->db->prepare("UPDATE wallet SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function addMoney($id,$amount){
        $sql = $this->db->prepare("UPDATE wallet SET balance= balance + ? WHERE id=?");
        $sql->bind_param('di',$amount,$id);
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function readWallet($column = '*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM wallet WHERE $condition");
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }

    public function countWallet($condition){
        $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `wallet` WHERE ".$condition);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

    public function createWallet($id,$amount,$pin,$status){
        $sql = $this->db->prepare("INSERT INTO wallet (`uid`,`balance`,`pin`,`status`,`errorcount`)
        VALUES(?,?,?,?,?)");
        $error = 0;
        $sql->bind_param('idsss',$id,$amount,$pin,$status,$error);
        if($sql->execute()){
            return true;
        }
    }

    public function addHistory($id,$amount,$type,$status){
        $sql = $this->db->prepare("INSERT INTO wallethistory (`uid`,`amount`,`type`,`date`,`status`)
        VALUES(?,?,?,?,?)");
        $date = date('Y-m-d H:i:s');
        $sql->bind_param('idsss',$id,$amount,$type,$date,$status);
        if($sql->execute()){
            return true;
        }
    }


    public function generateVoucher(){
        $code = mt_rand(100000000000, 900000000000);
        return $code;
    }


    public function addVoucher($code,$amount){
        $sql = $this->db->prepare("INSERT INTO voucher (`code`,`amount`,`date`,`status`)
        VALUES(?,?,?,?)");
        $date = date('Y-m-d H:i:s');
        $status = 'a';
        $sql->bind_param('idss',$code,$amount,$date,$status);
        if($sql->execute()){
            return true;
        }
    }
    
    public function updateVoucher($column,$condition){
        $sql = $this->db->prepare("UPDATE voucher SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function checkVoucher($condition){
        $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `voucher` WHERE ".$condition);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

    public function countHistory($condition){
        $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `wallethistory` WHERE ".$condition);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

    public function readVoucher($column = '*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM voucher WHERE $condition");
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }

    public function readHistory($column = '*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM wallethistory WHERE $condition");
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }

    public function updateWalletHistory($column,$condition){
        $sql = $this->db->prepare("UPDATE wallethistory SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function debitWallet($uid,$amount){
        $sql = $this->db->prepare("UPDATE wallet SET balance = balance - ? WHERE uid=?");
        $sql->bind_param('di',$amount,$uid);
        if($sql){
            $sql->execute();
            return true;
        }
    }



    public function createBookedBalance($uid,$pid,$sharer,$amount){
        $sql = $this->db->prepare("INSERT INTO `bookedbalance`(`pid`, `uid`, `sharer_id`, `amount`, `date`, `payment_status`) VALUES (?,?,?,?,?,?)");
        $date = date('Y-m-d');
        $status = 'i';
        $sql->bind_param('iiidss',$pid,$uid,$sharer,$amount,$date,$status);
        if($sql->execute()){
            return true;
        }
    }


    public function updateBookedBalance($pid,$sharer,$amount){
        $sql = $this->db->prepare("UPDATE `bookedbalance` SET amount= amount + ? WHERE pid=? AND sharer_id=?");
        $sql->bind_param('dii',$amount,$pid,$sharer);
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function countBookedBalance($pid,$sharer){
        $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `bookedbalance` WHERE pid=? AND sharer_id=?");
        $sql->bind_param('ii',$pid,$sharer);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

    public function totalBooked($uid){
        $sql = $this->db->prepare("SELECT SUM(amount) as total FROM `bookedbalance` WHERE sharer_id=? AND payment_status='i'");
        $sql->bind_param('i',$uid);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

}