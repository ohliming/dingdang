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
$type = getUrlParam('type',$str_url);
$pwd = getUrlParam('pwd',$str_url);
$new_pwd  = getUrlParam('new_pwd',$str_url);

$json_arr = array();
if ($type == 1){
	$tablename= 'guide';
}else{
	$tablename = 'tourist';
}

$update_sql = "UPDATE $tablename SET password = '$new_pwd' WHERE id = '$uid' and password = 'pwd'";
if(!mysql_query($update_sql,$dbcon)){
    $msg = "update password failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "update password success";
    $ret = 0;
}
$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>