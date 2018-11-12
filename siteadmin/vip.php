<?php
define('_VALID', true);
define('_ADMIN', true);
require '../include/config.php';
require '../include/function_admin.php';
require '../include/function_global.php';
require '../classes/auth.class.php';
Auth::checkAdmin();

$errors             = ( isset($_GET['err']) ) ? array($_GET['err']) : $errors;
$messages           = ( isset($_GET['msg']) ) ? array($_GET['msg']) : $messages;
$module             = ( isset($_GET['m']) && $_GET['m'] != '' ) ? trim($_GET['m']) : 'list';


$module_template = 'vip.tpl';
require 'modules/vip/' .$module. '.php';

$smarty->assign('errors', $errors);
$smarty->assign('messages', $messages);
$smarty->assign('module', $module);
$smarty->assign('sub_menu', 'vip');
$smarty->assign('active_menu', 'users');
$smarty->display('header.tpl');
$smarty->display('leftmenu/menu.tpl');
$smarty->display($module_template);
$smarty->display('footer.tpl');
?>
