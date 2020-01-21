<?php
session_start();

if(isset($_POST['login'],$_POST['code'])){

    if($_POST['code'] == "1234"){
        $_SESSION['aid'] = $_POST['code'];
        header('location:index.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
    <label for="Enter Code"></label>
    <input type="text" name="code">
    <button name="login">Login</button>
    </form>
</body>
</html>