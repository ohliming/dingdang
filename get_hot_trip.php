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
$count = getUrlParam('count',$str_url);

$BASE_FIELD = array('id','title','photo');
$request_sql = "SELECT id,title,photo FROM goods order by rem_score desc limit $count";
$context = get_result_set($dbcon,$request_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>