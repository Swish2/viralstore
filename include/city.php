<?php

$user_agent = $_SERVER['HTTP_USER_AGENT'];




$user_os        = getOS();
$user_browser   = getBrowser();

$device_details = "<strong>Browser: </strong>".$user_browser."<br /><strong>Operating System: </strong>".$user_os."";

print_r($device_details);

echo("<br /><br /><br />".$_SERVER['HTTP_USER_AGENT']."");

?>
