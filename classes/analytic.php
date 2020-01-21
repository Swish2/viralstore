<?php

class Analytic{
private $db = null;
private $user_agent = '';

public function __construct($db){
    $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
    $this->db = $db;
}


public function readVisit($pid){
    $sql = $this->db->prepare("SELECT total FROM returnvisit WHERE pid=$pid");
    $arr = array();
    if($sql->execute()){
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()){
            $arr[] = (object) $row;
        }
        return $arr;
    }
}

public function readClicks($condition){
    $sql = $this->db->prepare("SELECT SUM(total) FROM clicks WHERE $condition");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}


public function compareAllClicks($pid,$year){
    $sql = $this->db->prepare("SELECT SUM(total) as monthlyclick, MONTH(date) as currentmonth FROM clicks WHERE pid=? AND YEAR(date)=? GROUP BY MONTH(date)");
    $sql->bind_param('is',$pid,$year);
    $arr = array();
    if($sql->execute()){
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()){
            $arr[] = (object) $row;
        }
        return $arr;
    }
}

public function readCity($column='*',$condition){
   $sql = $this->db->prepare("SELECT $column FROM city WHERE $condition");
    $arr = array();
    if($sql->execute()){
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()){
            $arr[] = (object) $row;
        }
        return $arr;
    }
}

public function readHost($column='*',$condition){
    $sql = $this->db->prepare("SELECT $column FROM host WHERE $condition");
     $arr = array();
     if($sql->execute()){
         $result = $sql->get_result();
         while($row = $result->fetch_assoc()){
             $arr[] = (object) $row;
         }
         return $arr;
     }
 }

 public function readReferer($column='*',$condition){
    $sql = $this->db->prepare("SELECT $column FROM refferer WHERE $condition");
     $arr = array();
     if($sql->execute()){
         $result = $sql->get_result();
         while($row = $result->fetch_assoc()){
             $arr[] = (object) $row;
         }
         return $arr;
     }
 }

 

public function checkVisit($pid){
    $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `returnvisit` WHERE pid='$pid'");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}

public function checkClicks($pid){
    $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `clicks` WHERE pid='$pid'");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}

public function checkSharerClicks($pid,$sharer){
    $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `clicks` WHERE pid='$pid' AND sharer_id='$sharer'");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}


public function checkHost($host=null,$pid){
    $q = "SELECT count(*) AS `total` FROM `host` WHERE pid='$pid'";
    if($host != null){
        $q .=" AND name='$host'";
    }
    $sql = $this->db->prepare($q);
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}

public function checkCity($city=null,$pid){
    $q = "SELECT count(*) AS `total` FROM `city` WHERE pid='$pid'";
    if($city != null){
        $q .=" AND name='$city'";
    }
    $sql = $this->db->prepare($q);
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}


public function checkWorkforce($pid){
    $sql = $this->db->prepare("SELECT COUNT(id) as total FROM clicks WHERE pid='$pid'");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}

public function checkFingerprint($pid,$signature){
    $sql = $this->db->prepare("SELECT count(*) AS `total` FROM `fingerprint` WHERE sign='$signature' AND pid='$pid'");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}


public function checkReferer($ref=null,$pid){
    $q = "SELECT count(*) AS `total` FROM `refferer` WHERE pid='$pid'";
    if($ref != null){
        $q .=" AND name='$ref'";
    }
    $sql = $this->db->prepare($q);
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}
public function countReferrer($pid){
    $sql = $this->db->prepare("SELECT SUM(total) FROM `refferer` WHERE pid=$pid");
    $sql->execute();
    $sql->bind_result($total);
    if($sql->fetch()){
        return $total;
    }
}
public function updateCity($city,$pid){
    $sql = $this->db->prepare("UPDATE city SET total=total+1  WHERE name='$city' AND pid='$pid'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function updateHost($host,$pid){
    $sql = $this->db->prepare("UPDATE host SET total=  total+1  WHERE name='$host' AND pid='$pid'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function updateRefer($referer,$pid){
    $sql = $this->db->prepare("UPDATE refferer SET total=  total+1  WHERE name='$referer' AND pid='$pid'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function updateVisit($pid){
    $sql = $this->db->prepare("UPDATE returnvisit SET total= total+1  WHERE  pid='$pid'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function updateClicks($pid,$sharer_id){
    $sql = $this->db->prepare("UPDATE clicks SET total= total+1  WHERE  pid='$pid' AND sharer_id='$sharer_id'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function approveClick($pid,$sharer_id){
    $sql = $this->db->prepare("UPDATE clicks SET status= 'a'  WHERE  pid='$pid' AND sharer_id='$sharer_id'");
    if($sql){
        $sql->execute();
        return true;
    }
}

public function addVisit($pid){
    $sql = $this->db->prepare("INSERT INTO returnvisit (`pid`,`total`)
    VALUES(?,?)");
    $total = 0;
    $sql->bind_param('ii',$pid,$total);
    if($sql->execute()){
        return true;
    }
}

public function addClicks($pid,$sharer_id){
    $sql = $this->db->prepare("INSERT INTO clicks (`pid`, `sharer_id`,`total`,`date`)
    VALUES(?,?,?,?)");
    $total = 1;
    $date = date('Y-m-d');
    $sql->bind_param('iiis',$pid,$sharer_id,$total,$date);
    if($sql->execute()){
        return true;
    }
}

public function addCity($name,$pid){
    $sql = $this->db->prepare("INSERT INTO city (`pid`,`name`,`total`)
    VALUES(?,?,?)");
    $total = 1;
    $sql->bind_param('isi',$pid,$name,$total);
    if($sql->execute()){
        return true;
    }
}

public function addHost($name,$pid){
    $sql = $this->db->prepare("INSERT INTO host (`pid`,`name`,`total`)
    VALUES(?,?,?)");
    $total = 1;
    $sql->bind_param('isi',$pid,$name,$total);
    if($sql->execute()){
        return true;
    }
}

public function addReferer($name,$pid){
    $sql = $this->db->prepare("INSERT INTO refferer (`pid`,`name`,`total`)
    VALUES(?,?,?)");
    $total = 1;
    $sql->bind_param('isi',$pid,$name,$total);
    if($sql->execute()){
        return true;
    }
}


public function addFingerprint($pid,$signature){
    $sql = $this->db->prepare("INSERT INTO fingerprint (`pid`,`sign`) VALUES(?,?)");
    $sql->bind_param('is',$pid,$signature);
    if($sql->execute()){
        return true;
    }
}

public function getOS() { 

    

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $this->user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

    $browser        = "Unknown Browser";

    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $this->user_agent))
            $browser = $value;

    return $browser;
}


}