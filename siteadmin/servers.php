<?php
define('_VALID', true);
define('_ADMIN', true);
require '../include/config.php';
require '../include/function_global.php';
require '../include/function_admin.php';
require '../classes/auth.class.php';

Auth::checkAdmin();

if ( isset($_GET['err']) ) {
    $errors[]   = trim($_GET['err']);
}

if ( isset($_GET['msg']) ) {
    $messages[] = trim($_GET['msg']);
}

$module             = ( isset($_GET['m']) && $_GET['m'] != '' ) ? trim($_GET['m']) : 'all';
$module_keep        = NULL;
$module_template    = 'servers.tpl';
$modules_allowed    = array('all', 'add', 'edit');
if ( !in_array($module, $modules_allowed) ) {
    $module = 'all';
    $err    = 'Invalid Server Module!';
}

switch ( $module ) {
    case 'edit':
    case 'add':
        $module_template = 'servers_' .$module. '.tpl';
        break;
    case 'all':
    default:
        $module             = 'all';
        $module_template    = 'servers.tpl';
        break;
}
require 'modules/servers/' .$module. '.php';

$smarty->assign('errors', $errors);
$smarty->assign('err', $err);
$smarty->assign('messages', $messages);
$smarty->assign('module', $module);
$smarty->assign('active_menu', 'servers');
$smarty->display('header.tpl');
$smarty->display('leftmenu/menu.tpl');
$smarty->display($module_template);
$smarty->display('footer.tpl');
?>
