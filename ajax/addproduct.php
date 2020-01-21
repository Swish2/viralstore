<?php
session_start();
include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/user.php');
include_once('../library/img-upload/autoload.php');
include_once('../library/img-upload/samayo/bulletproof/src/utils/func.image-resize.php');

$db = new Database();
$con = $db->getConnection();
$user = new User($con);

function getExt($filename){
    $ext = end(explode('.',$fileName));
    return $ext;
}

if(isset($_POST['title'],$_POST['price'],$_POST['descs'],
$_POST['bid'],$_POST['img'],$_SESSION['uid'])){

    $uid = $_SESSION['uid'];
    $title = $con->real_escape_string(htmlspecialchars($_POST['title'],ENT_QUOTES));
    $price = $con->real_escape_string($_POST['price']);
    $bid = $con->real_escape_string($_POST['bid']);
    $descs = $con->real_escape_string(htmlspecialchars($_POST['descs'],ENT_QUOTES));

    
    $image_parts = explode(";base64,", $_POST['img']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = $_SESSION['uid'].'_'.time();
    $dir = 'uploads/'.$fileName.'.webp';
    if(file_put_contents('../'.$dir, $image_base64)){
        $product = new Products($con);
        $res = $product->addProduct($title,$price,$descs,$uid,$bid,$dir,'i');
        if($res){
         echo 200;
        }else{
         echo 0;
        }
    }
}