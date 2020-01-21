<?php
session_start();
$uid = $_SESSION['uid'];
if(!isset($_SESSION['uid'])){
    header('location:login');
}

$msg ='';
include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/wallet.php');
include_once('../classes/user.php');
include_once('../library/img-upload/autoload.php');
include_once('../library/img-upload/samayo/bulletproof/src/utils/func.image-resize.php');

$db = new Database();
$con = $db->getConnection();
$wallet = new Wallet($con);
$user = new User($con);



$notice ='';
$row = $user->readUser('wphone,bname','id='.$uid)[0];

if($row->bname == null || $row->wphone == null){
    $notice = '<div class="red white-text lighten-2 center" style="padding:5px; margin-bottom:20px;"><p>Account Information not complete. <a href="account" class="white-text"><u>Click here to update.</u></a></p></div>';
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Product | Viralstore</title>
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
    <link rel="icon" type="image/png" sizes="192x192" href="img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/fav/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                        <h5>NGN
                            <?php echo number_format($wallet->readWallet('balance','uid='.$_SESSION['uid'])[0]->balance); ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6">
                    <h5>New Product</h5>
                    <span>Note that informations entered are not editable after submission. Take your time to write the
                        right thing. </span>
                    <br />
                    <?php echo $msg; ?>
                    <br />
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col s12 m12 input-field">
                                <input type="text" placeholder="Product Name" id="title" name="title" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 input-field">
                                <input type="text" placeholder="Retail price" inputmode="decimal" class="only-numeric"
                                    id="price" name="price" required />
                            </div>
                            <div class="col s12 m12 input-field">
                                <input type="text" placeholder="Bid per click" inputmode="decimal" class="only-numeric"
                                    id="bid" name="bid" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 input-field">
                                <textarea id="descs" class="materialize-textarea" id="descs" name="descs"
                                    placeholder="Product Detailed Descriptions" required></textarea>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col s12 m12">
                                <h6>Product Photo</h6>
                                <div id="demo-1-container" class="jpreview-container"></div>

                                <input type="file" name="img" id="img" accept="image/*"
                                    class="uploaded-file orange lighten-2" data-jpreview-container="#demo-1-container"
                                    required>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col s12 m12">
                                <button class="btn blue" id="save" name="save">Save Product</button>
                                <div class="lds-ellipsis" style="display:none;">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col s12 m6"></div>
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
    let imgData;
    $(document).ready(function() {
        $('.uploaded-file').jPreview();
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
    });

    $(".only-numeric").bind("keypress", function(e) {
        var keyCode = e.which ? e.which : e.keyCode

        if (!(keyCode >= 48 && keyCode <= 57)) {
            $(".error").css("display", "inline");
            return false;
        } else {
            $(".error").css("display", "block");
        }
    });



    $('#img').change(function(e) {
        compress(e, function(r) {
            imgData = r;
        });
    });


    $('#save').click(function(e) {
        e.preventDefault();
        let a = $("input, textarea").filter(function() {
            return $.trim($(this).val()).length == 0
        }).length == 0;
        let img = '';
        if (a != true) {
            toastr.warning('One or more field is empty.');
            return false;
        }


        let formData = new FormData();
        formData.append('title', $('#title').val());
        formData.append('price', $('#price').val());
        formData.append('bid', $('#bid').val());
        formData.append('descs', $('#descs').val());
        formData.append('img', imgData);

        $.ajax({
            type: 'POST',
            url: '../ajax/addproduct.php',
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#save').addClass('disabled').text("Loading");
                $('.lds-ellipsis').css('display', 'block');
            },
            success: function(res) {
                if (res == 200) {
                    window.location.href = "product";
                } else {
                    $('.lds-ellipsis').css('display', 'none');
                    toastr.error('There is an error. Try again!');
                    $('#save').removeClass('disabled').text('Add Product');
                }
            }
        })

    });



    function compress(e, callback) {
        const width = 450;
        let height = 450;
        const fileName = e.target.files[0].name;
        const img = new Image();
        const reader = new FileReader();
        reader.readAsDataURL(e.target.files[0]);
        reader.onload = event => {
            img.src = event.target.result;
            img.onload = () => {
                    const elem = document.createElement('canvas');
                    const scaleFactor = width / img.width;
                    elem.width = width;
                    elem.height = img.height * scaleFactor;
                    const ctx = elem.getContext('2d');
                    // img.width and img.height will contain the original dimensions
                    ctx.drawImage(img, 0, 0, width, img.height * scaleFactor);
                    callback(ctx.canvas.toDataURL(img, 'image/webp', 1.0));

                },
                reader.onerror = error => console.log(error);
        };

    }
    </script>
</body>

</html>