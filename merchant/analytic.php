<?php
session_start();

if(!isset($_SESSION['uid'])){
    header('location:login');
}

$msg ='';
include_once('../classes/product.php');
include_once('../classes/database.php');
include_once('../classes/analytic.php');
include_once('../classes/user.php');


$db = new Database();
$con = $db->getConnection();
$user = new User($con);
$analytic = new Analytic($con);
$product = new Products($con);

$pid = '';
if(isset($_GET['pid']) && !empty($_GET['pid'])){
    $pid = $con->real_escape_string($_GET['pid']);
}else{
    header('location:product');
}


if($product->countProduct('id='.$pid) < 1){
    header('location:product');
}

$label = array();
$data = array();
$mo = $analytic->compareAllClicks($pid,date('Y'));

foreach($mo as $k=>$v){
    $dateObj   = DateTime::createFromFormat('!m', $v->currentmonth);
    $monthName = $dateObj->format('F');
    array_push($label,$monthName);
    array_push($data,$v->monthlyclick);
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Analytic | Viralstore</title>
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
    <link rel="stylesheet" href="js/loading/loading-bar.min.css">
    <link rel="stylesheet" href="../css/main.css" />

</head>

<body>
    <?php include_once('../include/header.php'); ?>
    <main>
        <div class="container">
            <br>
            <div class="row">
                <div class="col s12 m12">
                    <h5>Analytics</h5>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m4">
                    <div class="white a-item">
                        <p>TOTAL VISITS</p>
                        <p><img src="img/chart.png" alt="">
                            <span class="count right green lighten-4 center">
                                <?php 
                                if($analytic->checkClicks($pid) > 0){
                                    echo $analytic->readClicks('pid='.$pid);
                                }else{
                                    echo 0;
                                }
                                ?></span></p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="white a-item">
                        <p>WORKFORCE</p>
                        <p><img src="img/chart.png" alt="">
                            <span class="count right red lighten-4 center"><?php echo $analytic->checkWorkforce($pid); ?></span></p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="white a-item">
                        <p>RETURN VISIT</p>
                        <p><img src="img/chart.png" alt="">
                            <span class="count right blue lighten-4 center">
                                <?php
                                 if($analytic->checkVisit($pid) > 0){
                                    echo $analytic->readVisit($pid)[0]->total;
                                    }else{
                                        echo 0;
                                        }
                                        ?></span></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m8">
                    <div class="map white bar">
                        <p>Monthly Visit - Comparison</p>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="map white i-ref">
                        <p>REFERRER</p><br>
                        <?php
                            if($analytic->checkReferer('',$pid) > 0){
                                $ref = $analytic->readReferer('name,total','pid='.$pid);
                                $count = $analytic->countReferrer($pid);
                             foreach($ref as $k=>$v){
                                //     switch($v->name){
                                //         case 'https://facebook.com':
                                //             $perc = (100*$v->total)/$count;
                                //             echo '<div class="ref-wrapper">
                                //             <p><b>'.number_format($v->total).'</b><br>
                                //                 Facebook <span class="right">'.ceil($perc).'%</span></p>
                                //             <div class="progress blue lighten-4">
                                //                 <div class="determinate blue" style="width: '.ceil($perc).'%"></div>
                                //             </div>
                                //         </div>';
                                //         break;
                                //         case 'https://instagram.com':
                                //             $perc = (100*$v->total)/$count;
                                //             echo '<div class="ref-wrapper">
                                //             <p><b>'.number_format($v->total).'</b><br>
                                //                 Instagram <span class="right">'.ceil($perc).'%</span></p>
                                //             <div class="progress blue lighten-4">
                                //             <div class="determinate purple" style="width: '.ceil($perc).'%"></div>
                                //             </div>
                                //         </div>';
                                //         break;
                                //         case 'https://twitter.com':
                                //             $perc = (100*$v->total)/$count;
                                //             echo '<div class="ref-wrapper">
                                //             <p><b>'.number_format($v->total).'</b><br>
                                //                 Twitter <span class="right">'.ceil($perc).'%</span></p>
                                //             <div class="progress blue lighten-4">
                                //             <div class="determinate red lighten-2" style="width: '.ceil($perc).'%"></div>
                                //             </div>
                                //         </div>';
                                //         break;
                                //         default:
                                //         $perc = (100*$v->total)/$count;
                                //         echo'<div class="ref-wrapper">
                                //     <p><b>'.number_format($v->total).'</b><br>
                                //         Others <span class="right">'.ceil($perc).'%</span></p>
                                //     <div class="progress blue lighten-4">
                                //         <div class="determinate" style="width: '.ceil($perc).'%"></div>
                                //     </div>
                                // </div>';
                                //     break;

                                //     } 



                                if(parse_url($v->name)['host'] == parse_url('https://facebook.com')['host']){
                                    $perc = (100*$v->total)/$count;
                                    echo '<div class="ref-wrapper">
                                    <p><b>'.number_format($v->total).'</b><br>
                                        Facebook <span class="right">'.ceil($perc).'%</span></p>
                                    <div class="progress blue lighten-4">
                                        <div class="determinate blue" style="width: '.ceil($perc).'%"></div>
                                    </div>
                                </div>';
                                }
                                if(parse_url($v->name)['host'] == parse_url('https://instagram.com')['host']){
                                    $perc = (100*$v->total)/$count;
                                    echo'<div class="ref-wrapper">
                                    <p><b>'.number_format($v->total).'</b><br>
                                        Instagram <span class="right">'.ceil($perc).'%</span></p>
                                    <div class="progress blue lighten-4">
                                    <div class="determinate purple" style="width: '.ceil($perc).'%"></div>
                                    </div>
                                </div>';
                                }
                                if(parse_url($v->name)['host'] == parse_url('https://twitter.com')['host']){
                                    $perc = (100*$v->total)/$count;
                                   echo '<div class="ref-wrapper">
                                    <p><b>'.number_format($v->total).'</b><br>
                                        Twitter <span class="right">'.ceil($perc).'%</span></p>
                                    <div class="progress blue lighten-4">
                                    <div class="determinate red lighten-2" style="width: '.ceil($perc).'%"></div>
                                    </div>
                                </div>';
                                }
                                $e_re = array(parse_url('https://facebook.com')['host'],parse_url('https://twitter.com')['host'],parse_url('https://instagram.com')['host']);
                                    if(!in_array(parse_url($v->name)['host'], $e_re)){
                                    $perc = (100*$v->total)/$count;
                                   echo'<div class="ref-wrapper">
                                    <p><b>'.number_format($v->total).'</b><br>
                                        Others <span class="right">'.ceil($perc).'%</span></p>
                                    <div class="progress blue lighten-4">
                                        <div class="determinate" style="width: '.ceil($perc).'%"></div>
                                    </div>
                                </div>';
                                    }
                                

                             }
                            
                            }
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
        integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    <script src="../js/easypie.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown();
        $('.sidenav').sidenav();
    });


    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label); ?>,
            datasets: [{
                label: '',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: [
                    '#2196f3',
                    '#ff9800',
                    '#e65100',
                    '#4caf50',
                    '#3f51b5',
                ]
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>
</body>

</html>