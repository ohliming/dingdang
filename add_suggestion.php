<?php 

header("Content-type: text/html; charset=utf-8");
require 'utilities.php';
require_once 'config.php';
global $db_host,$db_user,$db_pass,$db_database;

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
$alias = getUrlParam('alias',$str_url);
$content = getUrlParam('content',$str_url);
$phone = getUrlParam('phone',$str_url);

$json_arr = array();

$insert_sql = "INSERT INTO suggestion(uid,alias,phone,content) VALUES($uid,'$alias','$content','$phone')";
if(!mysql_query($insert_sql,$dbcon)){
    $msg = "add suggestion failed：".mysql_error();
    $ret = 1001;
} else {
    $msg = "add suggestion success";
    $ret = 0;
}
$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>
