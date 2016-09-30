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
$guid = getUrlParam('guid',$str_url);

$BASE_FIELD = array('content1','picture1','content2','picture2','content3','picture3','content4','picture4','content5','picture5',
					'content6','picture6','content7','picture7','content8','picture8');

$response_sql = "select content1,picture1,content2,picture2,content3,picture3,content4,picture4,content5,picture5,content6,picture6,
					content7,picture7,content8,picture8 from guide a join g_intro t on t.id = a.intro_id where a.id = $guid";

$context = get_result_set($dbcon,$response_sql,$BASE_FIELD);

echo $context;
mysql_close($dbcon);

?>