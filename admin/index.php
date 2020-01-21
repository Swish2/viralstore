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

$user = new User($con);
$rUser = $user->readUser('img,fname,id','');

$product = new Products($con);
$wallet = new Wallet($con);

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
           <div class="row">
           <div class="col s12 m12">
           <br>
           <h5>Merchants ( <?php echo number_format($user->countUser('')); ?> )</h5>
           </div>
           </div>

           <div class="row">
           <div class="col s12">
           <table class="striped">
           <thead>
           <tr><th>#</th><th>Name</th><th>Products</th><th>Wallet</th><th>...</th></tr>
           </thead>
           <tbody>
           <?php
           $n = 1;
           $rWallet = '';
            foreach($rUser as $k=>$v){
               $rProduct = $product->countProduct("uid='$v->id'");
               if($wallet->countWallet('uid='.$v->id) > 0){
                $rWallet = $wallet->readWallet('balance','uid='.$v->id)[0]->balance;
               }else{
                $rWallet = '0.00';  
               }
               $rProductId= $product->readProduct('id','uid='.$v->id)[0]->id;
              
                echo '<tr><td>'.$n++.'.</td><td><a href="user.php?uid='.$v->id.'" class="green-text">'.ucwords($v->fname).'</a></td><td><a href="product.php?pid='.$rProductId.'" class="blue-text">'.number_format($rProduct).'</a></td><td><a href="wallet.php?id='.$v->id.'" class="red-text">'.number_format($rWallet).'</a></td><td></td></tr>';
            }
           ?>
           
           </tbody>
           </table>
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