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
$hot = getUrlParam('hot',$str_url);

if ($hot==1){
	$request_sql = "SELECT `code`,provincecode,`name`,icon_path,pinyin,zone_code FROM city WHERE recommend =1";

}else{
	$request_sql = "SELECT `code`,provincecode,`name`,icon_path,pinyin,zone_code FROM city";

}

$BASE_FIELD = array('id','parent_id','name','icon_path','pinyin','zone_code');
$context = get_result_set($dbcon,$request_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>