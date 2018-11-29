<?php
define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';
require 'include/function_smarty.php';

$page = $_GET['page'];


$sql        = "SELECT * FROM vip";
$rs         = $conn->execute($sql);
$vips       = $rs->getrows();

$smarty->assign('vips', $vips);

if(isset($page) && $page == 'buy'){
    
    $smarty->display('recharge_buy.tpl');
    
}else{
    $smarty->display('recharge.tpl');
}



$smarty->gzip_encode();
?>
