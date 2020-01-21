<?php
session_start();
require_once '../library/google/vendor/autoload.php';
require_once('../classes/database.php');
require_once('../classes/user.php');
require_once('../classes/wallet.php');

if(isset($_SESSION['uid'])){
  header('location:product');
}

// init configuration
$clientID = '421574205564-8c498acknb9g00mk24k4nktc4uqq7vhd.apps.googleusercontent.com';
$clientSecret = '8a-A2TZMR7RTM20wIfemvzOZ';
$redirectUri = 'https://viralstore.com.ng/merchant/login';
  $url = "";
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
 $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
  
  $db = new Database();
  $con = $db->getConnection();
  $user = new User($con);
  $wallet = new Wallet($con);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  
  $uid = $google_account_info->id;
  $email = $google_account_info->email;
  $name = $google_account_info->name;
  $picture = $google_account_info->picture;
  

  if($user->countUser($uid) < 1){
    $user->addUser($uid,$name,$email,$picture,'google','a');
    $lid = $con->insert_id;
    $wallet->createWallet($lid,0,'1234','a');
    $_SESSION['uid'] = $lid;
    header('location:product');
  }else{
      $_SESSION['uid'] = $user->readUser('id','uid='.$uid)[0]->id;
      header('location:product');
  }
 
} else {
   $url = $client->createAuthUrl();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Login | Viralstore</title>
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
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700|Roboto:400,500,700&display=swap"
        rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include_once('../include/login-header.php'); ?>
    <main style="height:inherit;">
        <div class="container">
            <div class="space"></div>
            <div class="row">
                <div class="col s12 m6">
                    <h4>Welcome</h4>
                    <p>Our system is smart enough to know if you already have an account with us or not.</p>
                    <p>It is very simple. Click the google button below and follow the prompts.</p>
                    <a href="<?php echo $url; ?>"><img src="../img/loginbtn.svg" class="login-btn" /></a>
                </div>
                <div class="col s12 m6">
                    <img src="../img/home2.jpg" class="responsive-img" />
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../include/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
    });
    </script>
</body>

</html>