<?php
session_start();
include_once('../classes/database.php');
include_once('../classes/user.php');
include_once('../classes/product.php');
include_once('../classes/wallet.php');

if(!isset($_SESSION['aid'])){
    header('location:login.php');
}

$db = new Database();
$con = $db->getConnection();
$rUser ='';
if(isset($_GET['uid'])){
    $user = new User($con);
    $uid = $_GET['uid'];
    $rUser = $user->readUser('img,fname,email,bname,info,wphone,address','id='.$uid)[0];
}else{
    header('location:index.php');
}




?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin | Viralstore</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="img/fav/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/fav/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/fav/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/fav/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/fav/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/fav/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/fav/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../img/fav/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/fav/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/fav/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/main.css" />
</head>

<body>
    <?php include_once('include/header.php'); ?>
    <main>
        <div class="container">
        <br>
        <div class="row">
        <div class="col s12 m12">
        <h5>User Profile</h5>
        </div>
        </div>
           <div class="row">
           <div class="col s12 m4">
           <div class="box white">
           <h6>Personal Profile</h6>
           <div class="divider"></div><br>
           <div class="center">
           <?php
         echo ' <img src="'.$rUser->img.'" alt="" class="circle profile">
           <h6>'.ucwords($rUser->fname).'</h6>
           <p>'.$rUser->email.'</p>';
           ?>
           </div>
           </div>
           </div>
           <div class="col s12 m8">
           <div class="box white">
           <h6>Business Profile</h6>
           <div class="divider"></div><br>
           <?php
           echo '<p><b>Business Name:</b></p>
           <p>'.ucwords($rUser->bname).'</p>
           <p><b>Address:</b></p>
           <p>'.$rUser->address.'</p>
           <p><b>Phone:</b></p>
           <p>'.$rUser->wphone.'</p>
           <p><b>Description:</b></p>
           <p>'.$rUser->info.'</p>';
           ?>
           </div>
           </div>
           </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    "use strict";
    $(document).ready(function() {


    });

  
    </script>
</body>

</html>