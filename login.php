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
$phone = getUrlParam('phone',$str_url);
$type = getUrlParam('type',$str_url);
$pwd = getUrlParam('pwd',$str_url);

if ($type == 1){
    $tablename= 'guide';
    $BASE_FIELD = array('id','sex','photo','alias','name','card_id','address','intro','intro_id','amount');
    $response_sql = "select id,sex,photo,alias,`name`,card_id,address,intro,intro_id,amount from $tablename where `password`='$pwd' and phone = '$phone'";
}else{
	$tablename = 'tourist';
	$BASE_FIELD = array('id','sex','photo','alias','name','card_id','address','intro','amount');
    $response_sql = "select id,sex,photo,alias,`name`,card_id,address,intro,amount from $tablename where `password`='$pwd' and phone = '$phone'";
}


$context = get_result_set($dbcon,$response_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>