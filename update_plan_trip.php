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
$id = getUrlParam('id',$str_url);
$title = getUrlParam('title',$str_url);
$tour_line = getUrlParam('tour_line',$str_url);
$content = getUrlParam('content',$str_url);
$count = getUrlParam('count',$str_url);
$price = getUrlParam('price',$str_url);
$start_time = getUrlParam('start_time',$str_url);
$end_time = getUrlParam('end_time',$str_url);
$address = getUrlParam('address',$str_url);
$days = getUrlParam('days',$str_url);

if ($start_time == ''){
    $start_time = '1990-01-01 00:00:00';
}

if ($end_time==''){
    $end_time = '1990-01-01 00:00:00';
}

$insert_sql = "update goods set tour_line = '$tour_line', title='$title',content='$content',price= $price ,address = '$address',
                start_time = '$start_time',end_time = '$end_time',days = $days where id = $id";

if(!mysql_query($insert_sql,$dbcon)){
    $msg = "update plan trip failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "update plan trip success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>