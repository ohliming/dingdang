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
$guid = getUrlParam('guid',$str_url);
$content = getUrlParam('content',$str_url);

$json_arr = array();
$insert_sql = "INSERT INTO g_intro(content1) values('$content')";

if(!mysql_query($insert_sql,$dbcon)){
    $msg = "add intro failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "add intro success";
    $ret = 0;
}


$intro_id= mysql_insert_id($dbcon);
$json_arr['intro_id'] = $intro_id;
$update_sql = "update guide set intro_id = $intro_id where id = $guid";
if(!mysql_query($update_sql,$dbcon)){
    $msg = "add intro failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "add intro success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>