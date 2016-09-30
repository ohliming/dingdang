<?php

header("Content-type: text/html; charset=utf-8");
require 'utilities.php';
require_once 'config.php';
global $db_host,$db_user,$db_pass,$db_database;

define('APP_DEBUG',True);

// 初始数据库连接
$dbcon = mysql_connect($db_host, $db_user, $db_pass);
if (!$dbcon) {
    die('Could not connect: ' . mysql_error());
}

mysql_query("set names utf8",$dbcon);
mysql_select_db($db_database,$dbcon);

// 获取请求信息
$str_url = $_SERVER["REQUEST_URI"];
$uid = getUrlParam('uid',$str_url);
$type = getUrlParam('type',$str_url);

$json_arr = array();

if ($type == 1){
    $tablename= 'guide';
}else{
    $tablename = 'tourist';
}

$url = $_FILES["photo_head"]["tmp_name"];
$newfname = time().'_'.mt_rand().".png";

$file = fopen ($url, "rb");
if ($file) {
    $newf = fopen ($newfname, "a");
    if ($newf){
        while(!feof($file)) {
            fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
        }
    }
}

if ($file) {
   fclose($file);
 }
 if ($newf) {
   fclose($newf);
}

rename($newfname,"../image/head/".$newfname);
$newfname = "http://101.201.72.82/image/head/".$newfname;
$update_sql = "update $tablename set photo = '$newfname' where id = $uid";

if(!mysql_query($update_sql,$dbcon)){
    $msg = "update failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "update success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['photo'] = $newfname;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));
echo $str_context;
mysql_close($dbcon);

?>