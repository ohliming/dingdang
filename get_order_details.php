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
$order_id = getUrlParam('id',$str_url);
$flag = getUrlParam('flag',$str_url);
$BASE_FIELD = array('id','title','photo','tour_line','content','count','price','address','start_time','end_time','days');

if($flag == 1){
	$response_sql = "select id, title, photo, tour_line, content, count, price, address, start_time, end_time,days from goods_book where id = $order_id";
}else{
	$response_sql = "select id, title, photo, tour_line, content, count, price, address, start_time, end_time,days from goods where id = $order_id";
}

$context = get_result_set($dbcon,$response_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);


?>
