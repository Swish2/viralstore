<?php
session_start();
include_once '../classes/database.php';
include_once '../classes/marketer.php';

if(isset($_POST['amount'],$_SESSION['mid'])){

    $db = new Database();
    $con = $db->getConnection();
    $mid = $_SESSION['mid'];
    $amount = $con->real_escape_string($_POST['amount']);
    $marketer = new Marketer($con);
    $d = $amount + 1000;
    if($marketer->checkEarningsBalance($mid)[0]->balance >= $d){
        if($marketer->withdrawEarnings($mid,$amount)){
            if($marketer->addTransaction($mid,$amount,'d','i')){
                echo 200;
            }else{
                echo 0;
            }
        }
    }else{
        echo 1;
    }
    
}