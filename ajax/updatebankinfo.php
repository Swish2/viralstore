<?php
session_start();
include_once '../classes/database.php';
include_once '../classes/payment.php';
include_once '../classes/marketer.php';



$arr = array('044'=>'Access Bank', '035A'=>'ALAT by WEMA','070'=>'Fidelity Bank','011'=>'First Bank of Nigeria','058'=>'Guaranty Trust Bank','032'=>'Union Bank of Nigeria','033'=>'United Bank For Africa','057'=>'Zenith Bank');


if(isset($_POST['bankcode'],$_POST['acctnumber'],$_POST['acctname'],$_SESSION['mid']) && is_numeric($_POST['acctnumber'])){
    $bankcode = (int) $_POST['bankcode'];
    $bankname = $arr[$_POST['bankcode']];
    $acctnumber = $_POST['acctnumber'];
    $acctname = $_POST['acctname'];
    $mid = $_SESSION['mid'];
    $db = new Database();
    $con = $db->getConnection();
    $marketer = new Marketer($con);
 
  $marketer->updateBankInfo($bankcode,$bankname,$acctnumber,$acctname,$mid);
      
       echo $con->error;
   
    
}else{
    echo 'empty';
}