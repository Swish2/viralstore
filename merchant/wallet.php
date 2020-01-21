<?php
session_start();

if(!isset($_SESSION['uid'])){
    header('location:login');
}
include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/wallet.php');
include_once('../classes/user.php');
include_once('../library/img-upload/autoload.php');


$msg = '';

$uid = $_SESSION['uid'];

$db = new Database();
$con = $db->getConnection();
$wallet = new Wallet($con);
$user = new User($con);

if(isset($_POST['load'],$_POST['voucher'])){
   $code = $_POST['voucher'];
   $wid = $wallet->readWallet('id,errorcount,status','uid='.$uid)[0];
   if($wid->status =='a'){
   
    if($wallet->checkVoucher("code='$code' AND status='a'") > 0){
        if($wid->errorcount <= 4){
        $voucher = $wallet->readVoucher('amount','code='.$code)[0];
        if($wallet->addMoney($wid->id,$voucher->amount)){
            if($wallet->updateVoucher("status='u', usedby='$uid'", 'code='.$code)){
                $wallet->addHistory($uid,$voucher->amount,'c','a');
               $msg = 'loaded';
            }
    }
    }else{

    }
    }else{
        $q = "errorcount = 'errorcount+1' ";
        if($wid->errorcount >=4){
            $q .= "status = 'a'";
        }
        $wallet->updateWallet($q,"id=".$wid->id);
        $msg ='used';
    }
        
   }else{
       $msg = 'blocked';
   }
}

$notice ='';
$row = $user->readUser('wphone,bname','id='.$uid)[0];

if($row->bname == null || $row->wphone == null){
    $notice = '<div class="red white-text lighten-2 center" style="padding:5px; margin-bottom:20px;"><p>Account Information not complete. <a href="account" class="white-text"><u>Click here to update.</u></a></p></div>';
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Wallet | Viralstore</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../css/main.css" />
</head>

<body>
    <?php include_once('../include/header.php'); ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col s12 m12">
                    <?php echo $notice; ?>
                    <!-- Modal Structure -->
                    <div id="modal1" class="modal">
                        <div class="modal-content">
                            <h5>Load Voucher</h5>
                            <br />
                            <div class="row">
                                <div class="col s12">
                                    <form method="post">
                                        <input type="text" name="voucher" placeholder="Enter Code" /><br /><br />
                                        <button class="btn green cta" name="load">Load Voucher</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
                        </div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col s12 m3">
                    <div class="wallet center">
                        <p class="green-text">Wallet Balance</p>
                        <h5>N <?php echo number_format($wallet->readWallet('balance','uid='.$uid)[0]->balance); ?></h5>
                    </div>
                </div>
                <div class="col s12 m3">
                    <div class="wallet center">
                        <p class="blue-text">Booked Balance</p>
                        <h5>N <?php echo 0.00; ?></h5>
                    </div>
                </div>
                <div class="col s12 m6"></div>
            </div>


            <div class="row">
                <div class="col s12 m4 offset-m8">
                    <a class="waves-effect waves-light btn purple modal-trigger cta" href="#modal1">Load Voucher</a>
                    <!-- <button class="btn btn-medium blue">Add Money</button> -->
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12">
                    <h5>Wallet</h5>
                    <div class="divider"></div>
                </div>
            </div>
            <br /><br />

            <div class="row">
                <div class="col s12 m12">
                    <h6>History</h6>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($wallet->countHistory('uid='.$uid) > 0){
                                $row = $wallet->readHistory('id,date,amount,type','uid='.$uid);
                                foreach($row as $k=>$v){
                            $type ="";
                                    switch($v->type){
                                        case 'd':
                                            $type = 'Debit';
                                        break;
                                            case 'c':
                                                $type ='Credit';
                                            break;
                                    }
                                    echo '<tr><td>'.$v->id.'</td><td>'.$v->date.'</td><td>'.number_format($v->amount).'</td><td>'.$type.'</td></tr>';
                                }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="../js/jpreview/jpreview.js"></script>
    <script>
    "use strict";
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
        $('.modal').modal();
        var msg = '<?php echo $msg; ?>';

        if (msg == 'loaded') {
            toastr.success('Voucher Loaded');
        } else if (msg == 'used') {
            toastr.warning(
                'Voucher Used/Invalid. Your account may be blocked if you enter the wrong code 3 times');
        } else if (msg == 'blocked') {
            toastr.error('Your wallet is locked. Contact customer care for help.');
        }

    });
    </script>
</body>

</html>
