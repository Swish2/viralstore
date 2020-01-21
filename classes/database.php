<?php

class Database{
     private $host = 'localhost';
    private $user ='root';
    private $password ='';
    private $database ='link';
    
    public function getConnection(){
          $db = new mysqli($this->host,$this->user,$this->password, $this->database);
        
        return $db;
    }
}