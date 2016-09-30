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
$address = getUrlParam('address',$str_url);
$page = getUrlParam('page',$str_url);
$pageSize = getUrlParam('pageSize',$str_url);
$start = ($page-1)*$pageSize;
$end = $page*$pageSize;

$BASE_FIELD = array('id','alias','photo','sex','intro');
$response_sql = "SELECT id,alias,photo,sex,intro from guide WHERE address= $address limit $start,$end";
$context = get_result_set($dbcon,$response_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>
