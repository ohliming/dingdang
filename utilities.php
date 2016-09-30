<?php

function getfirstchar($s0){   
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}
 
//获取中文拼音
function pinyin($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}

//解析URL参数
function parseUrlParam($query){
    $queryArr = explode('&', $query);
    $params = array();
    if($queryArr[0] !== ''){
        foreach( $queryArr as $param ){
            list($name, $value) = explode('=', $param);
            $params[urldecode($name)] = urldecode($value);
        }
    }
    return $params;
}

//获取URL参数
function getUrlParam($cparam, $url = ''){
    $parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
    $query = isset($parse_url['query']) ? $parse_url['query'] : '';
	$params = parseUrlParam($query);
    return isset($params[$cparam]) ? $params[$cparam] : '';
}

// 返回数据集合的json 字符串格式
function get_result_set($dbconn,$sql_info,$arr_field){
    $context = '{"items":[';
    $c_count = count($arr_field);
    $result_set = mysql_query($sql_info,$dbconn);
    $row_num = mysql_num_rows($result_set);

    // 获取查询结果数据
    while($row = mysql_fetch_row($result_set)){
        $json_arr = array();

        for($i=0;$i<$c_count;$i++){ // 获取一行数据
            $json_arr[$arr_field[$i]] = urlencode($row[$i]);
        }

        $context .= urldecode(json_encode($json_arr));
        if(--$row_num>0){
            $context .=',';
        }
    }

    if (isset($json_arr)){  // 输出json 格式的返回值
        $context .= '],"ret":0 }';
    }else{
        $context = '{ "ret": 1 }';
    }

    return $context;
}

/**
 * 函数介绍:    用于post方式提交数据
 * 输入参数:    完整url, 数据
 * 返回值  :    接口返回值
 */
function post_it($url, $data = '', $timeout = '6') {
    $urls = parse_url($url);
    if (!$urls) {
        return "-500";
    }
    $port = isset($urls['port']) ? $urls['port'] : null; //isset()判断
    if (!$port) {
        $port = "80";
    }
    $host = $urls['host'];
    //----------------------------------------------//
    $httpheader = "POST " . $url . " HTTP/1.0" . "\r\n" . "Accept:*/*" . "\r\n" . "Accept-Language:zh-cn" . "\r\n" . "Referer:" . $url . "\r\n" . "Content-Type:application/x-www-form-urlencoded" . "\r\n" . "User-Agent:Mozilla/4.0(compatible;MSIE 7.0;Windows NT 5.1)" . "\r\n" . "Host:" . $host . "\r\n" . "Content-Length:" . strlen($data) . "\r\n" . "\r\n" . $data;
    $fd = fsockopen($host, $port);
    if (!is_resource($fd)) {
        return "fsockopen failed";
    }
    fwrite($fd, $httpheader);
    stream_set_blocking($fd, TRUE);
    stream_set_timeout($fd, $timeout);
    $info = stream_get_meta_data($fd);
    $gets = "";
    while ((!feof($fd)) && (!$info['timed_out'])) {
        $data .= fgets($fd, 8192);
        $info = stream_get_meta_data($fd);
        @ob_flush();
        flush();
    }
    if ($info['timed_out']) {
        return "timeout";
    } else {
        //echo $data;
        $contentInfo = explode("\n\n", str_replace("\r", "", $data));
        
        if (!strstr($contentInfo[0], "HTTP/1.1 200 OK")) {
            return -10;
        }
        return trim($contentInfo[1]);
    }
}

?>