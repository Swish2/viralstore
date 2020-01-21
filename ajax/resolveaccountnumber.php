<?php
include_once '../classes/payment.php';

if(isset($_POST['bank'],$_POST['acctnumber']) && is_numeric($_POST['acctnumber'])){
    $bank = $_POST['bank'];
    $acctnumber = $_POST['acctnumber'];

    $payment = new Payment();
    $payment->resolveAccountNumber($bank,$acctnumber);
        echo $payment->getAccountName();
    
}