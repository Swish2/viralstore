<?php
session_start();
include_once('classes/database.php');
include_once('classes/product.php');
include_once('classes/urlshortner.php');

$db = new Database();
$con = $db->getConnection();

$product = new Products($con);

$arr = array();
$rProduct = $product->readProduct('id,img,title,price,bid', "status='a'");
if(is_array($rProduct)){
    foreach($rProduct as $key=>$val){
        $shortcode = Math::to_base($val->id);
      array_push($arr,array(
          'title'=>$val->title,'img'=>$val->img,'price'=>$val->price,'bid'=>$val->bid,'shortcode'=>$shortcode
      ));
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Products</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="../css/main.css" />

</head>

<body>
    <?php
    if(isset($_SESSION['mid'])){
    include_once('include/m-header.php');
        }else{
    include_once('include/header-front.php');
    }
    ?>
    <main>
        <div class="container">
            <br>
            <div class="row">
                <div class="col s12">
                    <h5>Promoted Products &amp; Services</h5>
                    <br >
                </div>
            </div>
            <div id="flex-wrapper">

            <?php

                $uid = 1;
            foreach($arr as $k=>$v){

                if(isset($_SESSION['mid'])){
                    $uid = $_SESSION['mid'];
                }else{
                    $uid = 1;
                }
               echo '<a href="https://viralstore.com.ng/store/'.$uid.'/'.$v['shortcode'].'"><div class="card small p-item">
                <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" src="'.$v['img'].'">
                </div>
                <div class="card-content">
                    <span class="card-title truncate black-text">'.ucwords($v['title']).'</span>
                    <p class="green-text"><b>Price: '.number_format($v['price']).'</b></p>';
                    if(isset($_SESSION['mid']) && !empty($_SESSION['mid'])){
                        echo '<p>Offer: #'.number_format($v['bid']).' PPC</p>';
                    }
                   
                echo'</div>
            </div></a>';
            }
            ?>
            </div>
        </div>
    </main>
    <form id="st" method="post"></form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/jquery.imagereader-1.1.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();

    });
    </script>
</body>

</html>