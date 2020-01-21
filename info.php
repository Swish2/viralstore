<?php
session_start();
include_once('classes/database.php');
include_once('classes/mlm.php');
include_once('classes/marketer.php');

$db = new Database();
$con = $db->getConnection();
$marketer = new Marketer($con);

// $rMarketer = $marketer->readMarketer('id','ref=20');

$r = $marketer->readChildNodes('1');
print_r($r);