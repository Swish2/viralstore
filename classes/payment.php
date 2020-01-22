<?php

class Payment{
    private $url;
    private $db;
    private $acctName = null;
    private $key ='';

    public function __construct($db = null){
        $this->db = $db;
    }

    private function setDate(){
        return date('Y-m-d H:i:s',time());
    }

    public function addPayment($wid,$course,$amount,$remaining){
        $sql = $this->db->prepare("INSERT INTO payment(`wid`,`amount``status`,`date`) VALUES(?,?,?,?)");
        $status = 'i';
        $verify = 'i';
        $date = date('Y-m-d H:i:s',time());
        $sql->bind_param('idss',$wid,$amount,$status,$date);
        if($sql){
            $sql->execute();
            return true;
        }
    }

    public function pay($email,$amount,$inv){

        $curl = curl_init();
        $amount = $amount * 100;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount'=>$amount,
                'email'=>$email,
                'reference'=>str_pad($inv,10,0,STR_PAD_LEFT),
                'callback_url'=>'https://codevolutionacademy.com/link/wallet'
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization:".$this->key, //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $tranx = json_decode($response, true);

           $this->url = $tranx['data']['authorization_url'];
            return true;
       
    }

    public function getUrl(){
        return $this->url;
    }

    public function verify($reference){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization:".$this->key,
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);


        $tranx = json_decode($response);

        if($tranx->status){
            return $tranx->message;
        }
        
    }



    public function resolveAccountNumber($bankCode,$acctNumber){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number={$acctNumber}&bank_code={$bankCode}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization:".$this->key,
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $tranx = json_decode($response);
     if($tranx->status){
         $this->acctName = $tranx->data->account_name;
         return true;
     }
    }

public function getAccountName(){
    return $this->acctName;
}

    public function confirmPayment($id,$student){
        $sql = $this->db->prepare("UPDATE payment SET status='a' WHERE student=? AND id=?");
        $sql->bind_param('ii',$student,$id);
        if($sql){
            $sql->execute();
            return true;
        }
    }
    
    public function readPayment($column = '*',$condition){
        $sql = $this->db->prepare("SELECT $column FROM payment WHERE $condition ORDER BY id DESC");
       $arr = array();
        if($sql){
            $sql->execute();
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                $arr[] = (object) $row;
            }
            return $arr;
        }
    }
    
     public function countPayment($uid){
        $sql = $this->db->prepare("SELECT count(*) FROM payment WHERE student=?");
        $sql->bind_param('i',$uid);
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
           return $total;  
        }
    }
     
    public function verifyPayment($condition){
        $sql = $this->db->prepare("SELECT count(*) FROM payment WHERE $condition");
        $sql->execute();
        $sql->bind_result($total);
        if($sql->fetch()){
           return $total;  
        }
    }
}
