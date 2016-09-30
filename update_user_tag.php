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
$json_arr = array();

// 获取请求信息
$str_url = $_SERVER["REQUEST_URI"];
$uid = getUrlParam('uid',$str_url);
$tag_id = getUrlParam('tag_id',$str_url);
$type = getUrlParam('type',$str_url);
$flag = getUrlParam('flag',$str_url);
$json_arr = array();

if ($flag == 1){
    $response_sql = "INSERT INTO user_tags(uid,tag_id,type,`status`) VALUES($uid,$tag_id,$type,$flag)";
}else{
    $response_sql = "UPDATE user_tags SET `status` = 0 WHERE uid = $uid and tag_id= $tag_id and type = $type";
}

if(!mysql_query($response_sql,$dbcon)){
    $msg = "change user tag fail".mysql_error();
     $ret = 1;
} else {
    $msg = "change user tag success";
     $ret = 0;
}

$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
if ($flag == 1){
	$f_id= mysql_insert_id($dbcon);
	$json_arr['id'] = $f_id;
}

$str_context = urldecode(json_encode($json_arr));

echo $str_context;
mysql_close($dbcon);;

?>
