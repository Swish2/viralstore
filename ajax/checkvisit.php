<?php
include_once('../classes/database.php');
include_once('../classes/analytic.php');
include_once('../classes/product.php');
include_once('../classes/wallet.php');
include_once('../classes/mlm.php');
include_once('../classes/marketer.php');
include_once('../classes/urlshortner.php');


use Josantonius\Cookie\Cookie;
function url($url) {
    $result = parse_url($url);
    return $result['scheme']."://".$result['host'];
  }
  
if(isset($_POST['code'],$_POST['sharer'],$_POST['signature'])){
        $city = "";

        $db = new Database();
        $con = $db->getConnection();

        $analytic = new Analytic($con);

        $sharer = (int) $con->real_escape_string($_POST['sharer']);
        $code = (int) Math::to_base_10($_POST['code']);
        $refferer =  url($_SERVER['HTTP_REFERER']);
        $signature = $_POST['signature'];
     
        $host = $analytic->getOs();
        $product = new Products($con);
        $pr = $product->readProduct('uid,bid','id='.$code)[0];
       
        if($analytic->checkFingerprint($code,$signature) == 0 && $pr->uid != $sharer){
        $analytic->addFingerprint($code,$signature);

        $ip = $_SERVER['REMOTE_ADDR'];
        $access_key = 'faa428cd1b475ea27d492615e9e1fee4';
        $ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);
        $city = $api_result['city'];

        if($analytic->checkCity($city,$code) > 0){
            $analytic->updateCity($city,$code);
        }else{
            $analytic->addCity($city,$code);
        }

        if($analytic->checkHost($host,$code) > 0){
            $analytic->updateHost($host,$code);
        }else{
            $analytic->addHost($host,$code);
        }

        if($analytic->checkReferer($refferer,$code) > 0){
            $analytic->updateRefer($refferer,$code);
        }else{
            $analytic->addReferer($refferer,$code);
        }
        $wallet = new Wallet($con);
        $mlm = new Mlm($con);
        $marketer = new Marketer($con);
        if($marketer->countMarketer('id='.$sharer) > 0){
            $levelid = $marketer->readMarketer('levelid','id='.$sharer)[0]->levelid;
            $levelamount = $mlm->readLevel('maxamount','id='.$levelid);

            if($wallet->totalbooked($sharer) < $levelamount){
                $bal = $wallet->readWallet('balance','uid='.$pr->uid)[0]->balance;
                if($bal >= $$pr->bid){
                    if($wallet->debitWallet($pr->uid,$pr->bid)){
                        if($wallet->countBookedBalance($code,$sharer) == 0){
                            $wallet->createBookedBalance($pr->uid,$code,$sharer,$pr->bid);
                        }else{
                            $wallet->updateBookedBalance($code,$sharer,$pr->bid);
                        }
        
                    if($analytic->checkSharerClicks($code,$sharer) < 1){
                        $analytic->addClicks($code,$sharer);  
                    }else{
                        $analytic->updateClicks($code,$sharer);
                    }
                }
                }
             
            }else{
                if($analytic->checkVisit($code) == 0){
                    $analytic->addVisit($code);
                }else{
                    $analytic->updateVisit($code);
                }
            }
        }
    }else{
        if($analytic->checkVisit($code) == 0){
            $analytic->addVisit($code);
        }else{
            $analytic->updateVisit($code);
        }
    }

    }