<?php
session_start();

if(!isset($_SESSION['uid'])){
    header('location:login');
}

$uid = $_SESSION['uid'];

$msg ='';
include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/wallet.php');
include_once('../classes/user.php');


$db = new Database();
$con = $db->getConnection();
$user = new User($con);

$notice ='';
$row = $user->readUser('wphone,email,fname,bname,address,info,img','id='.$uid)[0];

if($row->bname == null || $row->wphone == null){
    $notice = '<div class="red white-text lighten-2" style="padding:5px;"><p>Account Information not complete. <a href="account" class="white-text"><u>Click here to update.</u></a></p></div>';
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Account | Viralstore</title>
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
    <link rel="stylesheet" href="../css/main.css" />
    <style>
    .box{
    min-height: 300px;
    padding: 20px;
}

.profile{width: 100px;}
    </style>
</head>

<body>
    <?php include_once('../include/header.php'); ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col s12 m12 center"> <?php echo $notice; ?></div>
            </div>
            <div class="row">
                <div class="col s12 m4 center">
                    <div class="box white">
                        <img src="<?php echo $row->img; ?>" class="circle profile" style="width:30%;" />
                        <h6><b><?php echo $row->fname; ?></b></h6>
                        <p><?php echo $row->email; ?></p>
                        <br />

                    </div>
                </div>
                <div class="col s12 m8">
                    <div class="box">
                    <form method="post">
                        <div class="row">
                            <div class="col s12 m12">
                                <p><b>Business Info.</b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 input-field">
                                <input type="text" name="bname" id="bname" value="<?php echo $row->bname;?>"
                                    placeholder="Business Name" />
                                <input type="tel" inputmode="tel" name="wphone" id="wphone"
                                    value="<?php echo $row->wphone;?>" placeholder="Whatsapp Number" />
                                <input type="tel" name="address" id="address" value="<?php echo $row->address;?>"
                                    placeholder="Address" />
                                <textarea name="info" id="info" cols="30" rows="10"
                                    placeholder="Business Descriptions"><?php echo $row->info; ?></textarea>
                                <br><br>
                                <button class="btn cta primary-color" name="update" id="update">Update</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="../js/jpreview/jpreview.js"></script>
    <script>
    "use strict";
    $(document).ready(function() {

        var msg = '<?php echo $msg; ?>';
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();


    });

    $('#update').click(function(e) {
        e.preventDefault();

        let a = $("input, textarea").filter(function() {
            return $.trim($(this).val()).length == 0
        }).length == 0;

        if (a != true) {
            toastr.warning('One or more field is empty.');
            return false;
        }

        let formData = new FormData();
        formData.append('bname', $('#bname').val());
        formData.append('wphone', $('#wphone').val());
        formData.append('address', $('#address').val());
        formData.append('info', $('#info').val());
        $.ajax({
            data: formData,
            url: '../ajax/updateinfo.php',
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            beforeSend: function() {
                $('#update').addClass('disabled').text('Loading');
            },
            success: function(res) {
                $('#update').removeClass('disabled').text('Update');
                if (res == 200) {
                    toastr.success('Successfully updated');

                }
            }
        });
    });
    </script>
</body>

</html>