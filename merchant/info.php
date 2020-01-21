<?php

$url1 = parse_url('http://www.google.co.uk');
$url2 = parse_url('http://www.google.co.uk/pages.html');

echo $url1['host'];
// if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
//     echo 'We don\'t have mysqli!!!';
// } else {
//     echo 'Phew we have it!';
// }

//echo mt_rand(100000000000, 900000000000); 