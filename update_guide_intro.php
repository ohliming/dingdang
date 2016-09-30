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
$intro_id = getUrlParam('intro_id',$str_url);
$flag = getUrlParam('flag',$str_url);

$url = $_FILES["picture_intro"]["tmp_name"];
$newfname = time().'_'.mt_rand().".png";

$file = fopen ($url, "rb");
if ($file) {
    $newf = fopen ($newfname, "a");
    if ($newf){
        while(!feof($file)) {
            fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
        }
    }
}

if ($file) {
   fclose($file);
 }
 if ($newf) {
   fclose($newf);
}

rename($newfname,"../image/intro/".$newfname);
$newfname = "http://101.201.72.82/image/intro/".$newfname;

if (strpos($str_url, 'content') !== false){
	$content = getUrlParam('content',$str_url);
	$update_sql = "update g_intro set picture$flag = '$newfname', content$flag = '$content'  where id = $intro_id";
}else{
	$update_sql = "update g_intro set picture$flag = '$newfname' where id = $intro_id";
}

if(!mysql_query($update_sql,$dbcon)){
    $msg = "update failed：".mysql_error();
    $ret = 1;
} else {
    $msg = "update success";
    $ret = 0;
}
$json_arr = array();

$json_arr['photo'] = $newfname;
$json_arr['msg'] = $msg;
$json_arr['ret'] = $ret;
$str_context = urldecode(json_encode($json_arr));
echo $str_context;
mysql_close($dbcon);

?>