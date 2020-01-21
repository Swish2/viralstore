<?php
class Products{
    private $db = null;
    

    public function __construct($db){
        $this->db = $db;
    }

    public function addProduct($title,$price,$descs,$uid,$bid,$img,$status){
        $sql = $this->db->prepare("INSERT INTO product (`title`,`price`,`descs`,`uid`,`bid`,`img`,`date`,`status`)
        VALUES(?,?,?,?,?,?,?,?)");
            $date = date('Y-m-d H:i:s');
        $sql->bind_param('sdsidsss',$title,$price,$descs,$uid,$bid,$img,$date,$status);

        if($sql->execute()){
            return true;
        }
    }

    
    public function readProduct($column = '*',$condition = null){
        $q = "SELECT $column FROM product ";

        if($condition != null){
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

    public function updateProduct($column,$condition){
        $sql = $this->db->prepare("UPDATE product SET $column WHERE $condition");
        if($sql){
            $sql->execute();
            return true;
        }else{
            return $this->db->error;
        }
    }

    public function promotionProductToggle($status,$pid){
        $sql = $this->db->prepare("UPDATE product SET status =? WHERE id=?");
        $sql->bind_param('si',$status,$pid);
        if($sql){
            $sql->execute();
            return true;
        }else{
            return $this->db->error;
        }
    }

    public function deleteProduct($id){
        $sql = $this->db->prepare("DELETE FROM product WHERE id=$id");
        if($sq->execute()){
            return true;
        }
    }


    public  function getProductGallery($id){
        $sql = $this->db->prepare("SELECT * FROM productgallery WHERE product_id=?");
        $sql->bind_param('i',$id);
        $arr = array();
        if($sql->execute()){
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    } 
    
    public function countProduct($condition){
        $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `product` WHERE ".$condition);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
            return $total;
        }
    }

}
