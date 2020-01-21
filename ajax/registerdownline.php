<?php
session_start();
include_once('../classes/database.php');
include_once('../classes/marketer.php');
include_once('../classes/wallet.php');
include_once('../library/Mail/mail.php');


function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if(isset($_POST['bname'],$_POST['phone'],$_POST['email'],$_POST['code'],$_SESSION['mid'])){

$db = new Database();
$con = $db->getConnection();
$marketer = new Marketer($con);
$wallet = new Wallet($con);

$ref = $_SESSION['mid'];
$bname = $con->real_escape_string($_POST['bname']);
$email = $con->real_escape_string($_POST['email']);
$phone = $con->real_escape_string($_POST['phone']);
$code = $con->real_escape_string($_POST['code']);
$amount = 5000;
$levelid = 1;
$pwd = randomPassword();
$hashedpwd = md5($pwd);
$from = 'noreply@viralstore.com.ng';
$subject = 'Welcome | Account Info';
$body ='Dear '.$bname.', we are glad to have you onboard.
We are a team of passionate social media users working together to help businesses succeed online.
Beyond business owners, our platform provide you the opportunity to build a team and make passive income.
Your sponsor asked us to send you your login details. See them below.
Username: '.$email.'
Password: '.$pwd.'

If you need more information, kindly ask your sponsor.
Once again thank you for joining us.';
$senderName = 'Viralstore';
if($wallet->checkVoucher("code='$code' AND status='a' AND amount='$amount'") > 0){
    $wallet->updateVoucher("status='u',usedby='$ref'",'code='.$code);
    if($marketer->countMarketer('sponsor='.$ref) < 3){
    if($marketer->registerMarketer($bname,$phone,$email,$ref,$levelid,$hashedpwd)){
        $mail = new Mail(array($email=>$bname),$from,$subject,$body,$senderName);
        $mail->sendMail();
        echo 200;
    }
}
}else{
    echo 100;
}

}else{
    echo 0;
}