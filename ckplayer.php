<?php
define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';
require 'include/function_smarty.php';
    

$sql = "SELECT id,name,code FROM adv_pause WHERE status = 1 LIMIT 1";

$rs  = $conn->execute($sql);
$datas  = $rs->getrows();


$res['status'] = 0;

if($datas){
    $res['status'] = 1;
    
    echo(($datas[0]['code']));
    exit();
}


echo(json_encode($res));
exit();




?>
