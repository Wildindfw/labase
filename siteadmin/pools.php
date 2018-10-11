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
$module_template    = 'pools.tpl';
$modules_allowed    = array('all', 'pics', 'texts', 'videos');
if ( !in_array($module, $modules_allowed) ) {
    $module = 'all';
    $err    = 'Invalid Videos Module!';
}

switch ( $module ) {
    case 'videos':
		$module_keep        = $module;
        $module             = 'videos';
        $module_template    = 'pools_videos.tpl';
		break;
	case 'texts':
		$module_keep        = $module;
        $module             = 'texts';
        $module_template    = 'videos.tpl';
		break;
	case 'pics':
		$module_keep        = $module;
        $module             = 'pics';
        $module_template    = 'videos.tpl';
		break;
    default:
        $module_keep        = $module;
        $module             = 'all';
        $module_template    = 'videos.tpl';
        break;
}

if ( in_array($module, array('all', 'pics', 'texts', 'videos')) ) {
	$sub_menu = 'manage-'.$module;
}

include 'modules/pools/' .$module. '.php';

$smarty->assign('messages', $messages);
$smarty->assign('info', $info);
$smarty->assign('warnings', $warnings);
$smarty->assign('errors', $errors);

if ($module_keep) {
	$smarty->assign('module', $module_keep);
} else {
	$smarty->assign('module', $module);
}

$smarty->assign('active_menu', 'videos');
$smarty->assign('sub_menu', $sub_menu);
$smarty->display('header.tpl');
$smarty->display('leftmenu/menu.tpl');
$smarty->display($module_template);
$smarty->display('footer.tpl');
?>
