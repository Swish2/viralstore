<?php
session_start();
include_once('classes/database.php');
include_once('classes/product.php');
include_once('classes/urlshortner.php');
include_once('classes/wallet.php');
include_once('classes/user.php');

$uid = 0;
$u = '';
$base = 'https://viralstore.com.ng/';
$db = new Database();
$con = $db->getConnection();

if(isset($_GET['code'], $_GET['sharer']) &&
!empty($_GET['sharer']) && !empty($_GET['code'])){

$code = Math::to_base_10($_GET['code']);
$sharer = $_GET['sharer'];

$product = new Products($con);

if($n = $product->countProduct('id='.$code) > 0){
$res = $product->readProduct('uid,status,title,bid,price,descs,img','id='.$code)[0];

if($res->status == 'a' && $res->uid != $sharer){
        $wallet = new Wallet($con);
        $bal = $wallet->readWallet('balance','uid='.$res->uid)[0]->balance;
        if($bal < $res->bid){
            header('location:/home');
        }
}else if($res->status =='i' && $res->uid != $sharer){
    header('location:/home'); 
}
$user = new User($con);
$u = $user->readUser('fname,bname,wphone,img,address,info','id='.$res->uid)[0];

}else{
    header('location:/home');
}

}else{
    header('location:/home');
}




?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $res->title; ?></title>
    <base href="<?php echo $base; ?>" />
    <title><?php echo $res->title; ?></title>
    <meta property="og:site_name" content="Viral Store" />
    <meta property="og:title" content="<?php echo $res->title; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="<?php echo substr($res->descs, 0,100); ?>" />
    <meta property="og:url" content="https://viralstore.com.ng/store/<?php echo $_GET['sharer'].'/'.$_GET['code'] ?>" />
    <meta property="og:image" content="https://viralstore.com.ng/<?php echo $res->img; ?>" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />
    <meta name="title" content="<?php echo $res->title; ?>" />
    <meta name="description" content="<?php echo substr($res->descs, 0,100); ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="img/fav/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/fav/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/fav/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/fav/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/fav/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/fav/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/fav/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/fav/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/fav/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/fav/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700|Roboto:400,500,700|Montserrat:300,400&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <?php
if(isset($_SESSION['uid'])){
    include_once('include/header.php');
}else{
    include_once('include/header-front.php');
}
      ?>
    <main>
        <div class="container">
            <div class="space"></div>
            <section>
                <div class="row">
                    <div class="col s12 m6">
                        <img src="img/placeholder.gif" data-src="<?php echo $res->img; ?>" class="responsive-img lazyload" />
                    </div>
                    <div class="col s12 m6">
                        <h3><?php echo $res->title; ?></h3>
                        <h5 class="price">Price: #<?php echo number_format($res->price); ?></h5>
                        <p><?php echo nl2br(stripcslashes($res->descs)); ?></p>
                        <a href="https://wa.me/<?php echo $u->wphone; ?>" class="btn cta green">Contact Seller on
                            Whatsapp</a>
                    </div>
                </div>
            </section>
        </div>
        <div class="space"></div>
        <section id="seller">
            <div class="container">
                <div class="row">
                    <div class="col s12 m12">
                        <h5>Seller Information</h5>
                        <br>
                        <div class="row">
                            <div class="col s12 m5">
                                <div class="row valign-wrapper">
                                    <div class="col s12 m3">
                                        <img src="<?php echo $u->img; ?>" alt="" class="circle responsive-img">
                                        <!-- notice the "circle" class -->
                                    </div>
                                    <div class="col s12 m9">
                                        <p><b>Name: <?php echo $u->fname; ?></b><br>(<?php echo $u->bname; ?>)</p>
                                        <p><b>Address:</b> <?php echo $u->address; ?><p>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m7">
                                <p><?php echo nl2br(stripcslashes($u->info)); ?></p>
                                <a href="https://wa.me/<?php echo $u->wphone; ?>" class="btn cta primary-color">Contact
                                    Seller on Whatsapp</a>

                            </div>
                        </div>

                    </div>
        </section>
    </main>
    <?php include_once('include/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.min.js"
        integrity="sha256-S/UuH5LOnqk/MwJZQ9ANv+XnP/HI3cFQeu6KyC003A8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
    $(document).ready(function() {
        'use strict'
        lazyload();
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
        let code = '<?php echo $_GET['code']; ?>';
        let sharer = '<?php echo $_GET['sharer']; ?>';

        if (window.requestIdleCallback) {
            requestIdleCallback(function() {
                Fingerprint2.get(function(components) {
                    let signature = Fingerprint2.x64hash128(components.map(function(pair) {
                        return pair.value
                    }).join(), 31);

                    $.post('ajax/checkvisit.php', {
                        code: code,
                        sharer: sharer,
                        signature: signature
                    }, function(res) {
console.log(res);
                    });

                });
            });
        }

    });
    </script>
</body>

</html>