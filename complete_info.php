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
$sex = getUrlParam('sex',$str_url);
$alias = getUrlParam('alias',$str_url);
$name = getUrlParam('name',$str_url);
$card_id = getUrlParam('card_id',$str_url);
$address = getUrlParam('address',$str_url);
$intro = getUrlParam('intro',$str_url);

$json_arr = array();
if ($type == 1){
    $tablename= 'guide';
}else{
    $tablename = 'tourist';
}

$update_sql = "update $tablename set sex = $sex,alias = '$alias' ,name = '$name',card_id = $card_id ,
                address = $address, intro = '$intro' where id = $uid";

if(!mysql_query($update_sql,$dbcon)){
    $msg = "update failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "update success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));
echo $str_context;
mysql_close($dbcon);

?>
