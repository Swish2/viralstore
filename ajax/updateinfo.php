<?php
session_start();

include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/user.php');

if(isset($_POST['wphone'],$_POST['bname'],$_POST['info'],$_POST['address'],$_SESSION['uid'])){
    $uid = $_SESSION['uid'];

    $db = new Database();
    $con = $db->getConnection();
    $user = new User($con);
    $wphone = $con->real_escape_string($_POST['wphone']);
    $wphone = preg_replace('/\s+/', '', $wphone);
    $wphone = preg_replace('/^0/','234',$wphone);
    $bname = $con->real_escape_string($_POST['bname']);
    $address = $con->real_escape_string($_POST['address']);
    $info = $con->real_escape_string($_POST['info']);
 
   if($user->updateUser("wphone='$wphone', bname='$bname', address='$address',info='$info'","id='$uid'")){
    echo 200;
   }else{
       echo 0;
   }
   
}else{
    echo 'empty';
}