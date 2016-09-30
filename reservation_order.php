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
$type = getUrlParam('type',$str_url);
$good_id = getUrlParam('good_id',$str_url);
$price = getUrlParam('price',$str_url);

$start_time = getUrlParam('start_time',$str_url);
$end_time = getUrlParam('end_time',$str_url);
$json_arr = array();

$insert_sql = "INSERT INTO goods_book(good_id,initiator_id,tour_line,title,photo,thum_photo,content,guid,tuid,type,price,count,days,`status`
				,start_time,end_time,create_time,address,rem_score) SELECT id,initiator_id,tour_line,title,photo,thum_photo,content,guid,
				tuid,type,price,count,days,1,start_time,end_time,create_time,address,rem_score FROM goods WHERE id = $good_id";

echo $insert_sql;
if(!mysql_query($insert_sql,$dbcon)){
    $msg = "reservation oreder fail".mysql_error();
    $ret = 1;
} else {
    $msg = "reservation oreder success";
    $ret = 0;
}

$book_id= mysql_insert_id($dbcon);
$days = floor((strtotime($end_time)-strtotime($start_time))/86400) +1;
if ($type == 1){
	$update_sql = "UPDATE goods_book SET price = $price,start_time = '$start_time',end_time = '$end_time',guid = $uid,days = $days  WHERE id = $book_id";
}else{
	$update_sql = "UPDATE goods_book SET price = $price,start_time = '$start_time',end_time = '$end_time',tuid = $uid,days = $days  WHERE id = $book_id";
}

if(!mysql_query($update_sql,$dbcon)){
    $msg = "reservation oreder fail".mysql_error();
    $ret = 1;
} else {
    $msg = "reservation oreder success";
    $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);

?>
