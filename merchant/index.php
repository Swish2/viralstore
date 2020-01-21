<?php
session_start();
include_once('../classes/database.php');
include_once('../classes/product.php');
include_once('../classes/user.php');
include_once('../classes/wallet.php');
include_once('../classes/urlshortner.php');
include_once('../classes/analytic.php');

if(!isset($_SESSION['uid'])){
    header('location:login');
}

$uid = $_SESSION['uid'];

$arr = array();

    $db = new Database();
    $connection = $db->getConnection();
    $wallet = new Wallet($connection);
    $product = new Products($connection);
    $analytic = new Analytic($connection);


if(isset($_POST['pstatus'],$_POST['pid'])){
    $ps = $_POST['pstatus'];
    $pid = $_POST['pid'];
    $product->promotionProductToggle($ps, $pid);
    
}

    $row = $product->readProduct('title,price,bid,id,status','uid='.$uid);
    
    if(is_array($row)){
        foreach($row as $key=>$val){
            $shortcode = Math::to_base($val->id);
          array_push($arr,array(
              'id'=>$val->id,'title'=>$val->title,'price'=>$val->price,'bid'=>$val->bid,'shortcode'=>$shortcode,'status'=>$val->status
          ));
        }
    }

$user = new User($connection);
    $notice ='';
    $row = $user->readUser('wphone,bname','id='.$uid)[0];
    
    if($row->bname == null || $row->wphone == null){
        $notice = '<div class="red white-text lighten-2 center" style="padding:5px; margin-bottom:20px;"><p>Account Information not complete. <a href="account" class="white-text"><u>Click here to update.</u></a></p></div>';
    }    

?>

<!DOCTYPE html>
<html>

<head>
    <title>Products | viralstore</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="../img/fav/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../img/fav/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../img/fav/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../img/fav/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../img/fav/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../img/fav/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../img/fav/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../img/fav/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/fav/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/fav/favicon-16x16.png">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="../css/main.css" />

</head>

<body>
    <?php include_once('../include/header.php'); ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col s12 m12">
                    <?php echo $notice; ?>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m3 offset-m9">
                    <div class="wallet center">
                        <p>Wallet Balance</p>
                        <h5>NGN <?php echo number_format($wallet->readWallet('balance','uid='.$uid)[0]->balance); ?>
                        </h5>
                    </div>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col s12 m3 offset-m9 center">
                    <a href="add" class="btn cta blue">Add Product</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <h5>Products</h5>
                    <br />
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Bid</th>
                                <th>Clicks</th>
                                <th>URL</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                $n = 1;
                $clicks = 0;
                
                foreach($arr as $k=>$v){
                    
                   echo '<tr><td>'.$n++.'.</td><td>'.$v['title'].'</td><td>'.number_format($v['price']).'</td><td>'.number_format($v['bid']).'</td><td><a href="analytic.php?pid='.$v['id'].'">';
                   if($analytic->checkClicks($v['id']) > 0){
                    $c = $v['id'];
                   echo $analytic->readClicks('pid='.$c).'</a></td>';
                   }else{
                    echo '0</a></td>';
                   }
                 echo  '<td><a href="https://viralstore.com.ng/store/'.$uid.'/'.$v['shortcode'].'"><i class="material-icons">visibility</i></a></td><td><div class="switch">';
                    
                    if($v['status'] == 'a'){
                        echo '<label>
                        <input type="checkbox" class="checkbox_check" value="'.$v['id'].'" checked>
                        <span class="lever"></span>
                        On
                      </label>
                    </div></td></tr>';
                    }else{
                        echo '<label>
                        <input type="checkbox" class="checkbox_check" value="'.$v['id'].'">
                        <span class="lever"></span>
                        On
                      </label>
                    </div></td></tr>';
                    }
                    
                }

            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
    <form id="st" method="post"></form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../js/jquery.imagereader-1.1.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
        $('input[type=checkbox]').click(function() {
            var id = '';
            if ($('input.checkbox_check').is(':checked')) {
                id = $(this).val()
                $('#st').append(`<input type="hidden" name="pstatus" value="a">
           <input type="hidden" name="pid" value="${id}">`).submit();
            } else {
                id = $(this).val()
                $('#st').append(`<input type="hidden" name="pstatus" value="i">
           <input type="hidden" name="pid" value="${id}">`).submit();
            }
        })
    });
    </script>
</body>

</html>