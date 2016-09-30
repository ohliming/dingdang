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
$page = getUrlParam('page',$str_url);
$pageSize = getUrlParam('pageSize',$str_url);
$type = getUrlParam('type',$str_url);

$start = ($page-1)*$pageSize;
$end = $page * $pageSize;

$BASE_FIELD = array('id','title','photo','tour_line','price','days','start_time','end_time');
$response_sql="select id,title,photo,tour_line,price,days,start_time,end_time from goods where initiator_id = $uid and type = $type order by create_time desc limit $start,$end";

$context = get_result_set($dbcon,$response_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>