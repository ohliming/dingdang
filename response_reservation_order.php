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
$json_arr = array();

// 获取请求信息
$str_url = $_SERVER["REQUEST_URI"];
$id = getUrlParam('id',$str_url);
$status = getUrlParam('status',$str_url);

$response_sql = "UPDATE goods_book set `status` = $status WHERE id = $id";
if(!mysql_query($response_sql,$dbcon)){
    $msg = "response oreder fail".mysql_error();
    $ret = 1;
} else {
    $msg = "response oreder success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);


?>
