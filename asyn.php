<?php
define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';
require 'include/function_smarty.php';

//类型
$t       = $_GET['t'];
//页码
$p       = $_GET['p'];
//每页显示条数
$s       = $_GET['s'];
//排序字段
$o       = $_GET['o'];



if(!is_numeric($s)){
    $s = 8;
}


if(!is_numeric($p)){
    $p = 1;
}

$orders         = array('bw', 'mr', 'mv', 'tr', 'md', 'tf', 'lg');
$order          = ( isset($_GET['o']) && in_array($_GET['o'], $orders) ) ? $_GET['o'] : 'mr';


$sql_add_count  = NULL;

$sql_add       =  " WHERE active = '1'";

switch ( $order ) {
    case 'br':
        $sql_order .= " ORDER BY viewtime DESC";
        break;
    case 'mr':
        $sql_order .= " ORDER BY addtime DESC";
        break;
    case 'mv':
        $sql_order .= " ORDER BY viewnumber DESC";
        break;
    case 'tr':
        $sql_order .= " ORDER BY (ratedby*rate) DESC";
        break;
    case 'md':
        $sql_order .= " ORDER BY com_num DESC";
        break;
    case 'tf':
        $sql_order .= " ORDER BY fav_num DESC";
        break;
    case 'lg':
        $sql_order .= " ORDER BY duration DESC";
        break;
}

$page_items = $s;
$limit = $page_items;
if($p >= 2){
    $limit = ($p - 1) * $page_items. ', ' .$page_items;
}
    

if($t == 'view'){
   
    
    $sql = "SELECT VID, title, duration, addtime, thumb, thumbs, viewnumber, rate, likes, dislikes, type, hd,thumb_img
                   FROM video" .$sql_add.$sql_order ." LIMIT " .$limit;
    $rs             = $conn->execute($sql);
    $datas  = $rs->getrows();    
    
    
}else{
    
    $sql = "SELECT VID, title, duration, addtime, thumb, thumbs, viewnumber, rate, likes, dislikes, type, hd,thumb_img
                   FROM video" .$sql_add. $sql_order ." LIMIT " .$limit;
    
    
    $rs             = $conn->execute($sql);
    $datas  = $rs->getrows();
}

$img = array();


foreach ($datas as $k => $v) {
    
    
    
    $v['time'] = $v['addtime'];
    
    $v['addtime'] = insert_time_range($v);
    
    $v['duration'] = insert_duration($v);
    
    if($v['thumb_img'] != "0"){
        $v['thumb_img'] =  insert_thumb_path($v).'/'.$v['thumb_img'];
    }else{
        $v['vid'] = $v['VID'];
        $v['thumb_img'] =  insert_thumb_path($v).'/'.$v['thumb'].'.jpg';
    }    
    
    $datas[$k] = $v;
}


$res['datas'] = $datas;
$res['count'] = count($datas);
echo(json_encode($res));
exit();




?>
