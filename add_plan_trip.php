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
$uid = getUrlParam('uid',$str_url);
$type = getUrlParam('type',$str_url);
$title = getUrlParam('title',$str_url);
$tour_line = getUrlParam('tour_line',$str_url);
$content = getUrlParam('content',$str_url);
$count = getUrlParam('count',$str_url);
$price = getUrlParam('price',$str_url);
$start_time = getUrlParam('start_time',$str_url);
$end_time = getUrlParam('end_time',$str_url);
$address = getUrlParam('address',$str_url);
$initiator_id= $uid;
$days = getUrlParam('days',$str_url);
$json_arr = array();
if ($start_time == ''){
	$start_time = '1990-01-01 00:00:00';
}

if ($end_time==''){
	$end_time = '1990-01-01 00:00:00';
}

if ($type == 1){
	$guid= $uid;
	$tuid = -1;
}else{
	$tuid = $uid;
	$guid = -1;
}

if ($days == ''){
    $days = 0;
}

$insert_sql = "INSERT INTO goods(initiator_id,tour_line,title,content,guid,tuid,type,`status`,count,price,address,start_time,end_time,days) 
			VALUES('$initiator_id','$tour_line','$title','$content',$guid,$tuid,$type,1,$count,$price,$address,'$start_time','$end_time',$days)";

if(!mysql_query($insert_sql,$dbcon)){
    $msg = "add plan trip failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "add plan trip success";
    $ret = 0;
}
$f_id= mysql_insert_id($dbcon);
$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$json_arr['id'] = $f_id;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>