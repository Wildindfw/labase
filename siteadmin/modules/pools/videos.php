<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();

require $config['BASE_DIR']. '/include/function_video.php';
require $config['BASE_DIR']. '/include/function_smarty.php';

require $config['BASE_DIR']. '/classes/pagination.class.php';
require $config['BASE_DIR'].'/classes/DB.class.php';

//得到远端的视频分类
$db = DB::getIntance();
$where['parentid'] = 1;
$categorys = $db->selectAll('v9_category',$where,'catid,catname');

$remove = NULL;
$page   = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$query          = video_constructQuery($module_keep);
$sql            = $query['count'];
$rs             = $db->getRow($sql);
$total_videos   = $rs['total_videos'];
$pagination     = new Pagination($query['page_items']);
$limit          = $pagination->getLimit($total_videos);
$paging         = $pagination->getAdminPagination($remove);
$sql            = $query['select']. " LIMIT " .$limit;
$raw_videos         = $db->getAll($sql);
$videos = array();
foreach($raw_videos as $video){
	$data = string2array($video['data']);
	$videos[]['title'] 	   	= $data['title'];
	$videos[]['video_url'] 	= $data['video_url'];
	$videos[]['thumb'] 		= $video['thumb'];
	$videos[]['id'] 		= $video['id'];
}
function video_constructQuery($module){
	global $smarty;
	$query_module = '';
    $query              = array();
	$query_select       = "SELECT * FROM v9_collection_content WHERE status = 1" .$query_module;
    $query_count        = "SELECT count(*) AS total_videos FROM v9_collection_content WHERE status = 1" .$query_module;
	$all   = (isset($_GET['all'])) ? intval($_GET['all']) : 0;
	if ($all == 1) {
		unset ($_SESSION['search_guang_videos_option']);
	}
	$option_orig        = array('sort' => 'id', 'order' => 'DESC', 'display' => 100);
	$option             = ( isset($_SESSION['search_videos_option']) ) ? $_SESSION['search_videos_option'] : $option_orig;
	if ( isset($_POST['search_videos']) ) {
        $option['username']     = trim($_POST['username']);
        $option['title']        = trim($_POST['title']);
        $option['description']  = trim($_POST['description']);
        $option['keyword']      = trim($_POST['keyword']);
        $option['channel']      = intval(trim($_POST['channel']));
        $option['active']       = trim($_POST['active']);
        $option['type']         = trim($_POST['type']);		
		$option['sort']         = trim($_POST['sort']);		
        $option['order']        = trim($_POST['order']);
        $option['display']      = trim($_POST['display']);
	
		if ( $option['username'] != '' && !$UID) {
			$UID            = getUserID($option['username']);
			$UID            = ( $UID ) ? $UID : 0;
			$query_option[] = " AND v.UID = '" .mysql_real_escape_string($UID). "'";
		}

		if ( $option['title'] != '' ) {
			$query_option[] = " AND v.title LIKE '%" .mysql_real_escape_string($option['title']). "%'";
		}

		if ( $option['description'] != '' ) {
			$query_option[] = " AND v.description LIKE '%" .mysql_real_escape_string($option['description']). "%'";
		}
			
		if ( $option['keyword'] != '' ) {
			$query_option[] = " AND v.keyword LIKE '%" .mysql_real_escape_string($option['keyword']). "%'";
		}

		if ( $option['channel'] != '' ) {
			$query_option[] = " AND v.channel = " .intval($option['channel']);
		}
		
		if ( $option['type'] != '' ) {
			$query_option[] = " AND v.type = '" .mysql_real_escape_string($option['type']). "'";
		}
		if ( $option['active'] == '0' || $option['active'] == '1' ) {
			$query_option[] = " AND v.active = '" .$option['active']. "'";
		}
		$_SESSION['search_videos_option'] = $option;
	}elseif (isset($_SESSION['search_videos_option'])) {
		
		$option = $_SESSION['search_videos_option'];
		
		if ( $option['username'] != '' || isset($_GET['UID']) ) {
			if ( $option['username'] != '' ) {
				$UID            = getUserID($option['username']);
			} else {
				$UID            = ( isset($_GET['UID']) && is_numeric($_GET['UID']) ) ? $_GET['UID'] : 0;
			}
			$UID            = ( $UID ) ? $UID : 0;
			$query_option[] = " AND v.UID = '" .mysql_real_escape_string($UID). "'";
		}

		if ( $option['title'] != '' ) {
			$query_option[] = " AND v.title LIKE '%" .mysql_real_escape_string($option['title']). "%'";
		}

		if ( $option['description'] != '' ) {
			$query_option[] = " AND v.description LIKE '%" .mysql_real_escape_string($option['description']). "%'";
		}
			
		if ( $option['keyword'] != '' ) {
			$query_option[] = " AND v.keyword LIKE '%" .mysql_real_escape_string($option['keyword']). "%'";
		}

		if ( $option['channel'] != '' ) {
			$query_option[] = " AND v.channel = " .intval($option['channel']);
		}

		if ( $option['active'] == '0' || $option['active'] == '1' ) {
			$query_option[] = " AND v.active = '" .$option['active']. "'";
		}		
		
	}
	$query_option[]         = " ORDER BY " .$option['sort']. " " .$option['order'];    
    $query['select']        = $query_select .implode(' ', $query_option);
    $query['count']         = $query_count .implode(' ', $query_option);
    $query['page_items']    = $option['display'];
    
    $smarty->assign('option', $option);
    
    return $query;
}

function getUserID( $username )
{
    global $conn;
    
    $sql = "SELECT UID FROM signup WHERE username = '" .mysql_real_escape_string($username). "' LIMIT 1";
    $rs  = $conn->execute($sql);
    if ( $conn->Affected_Rows() == 1 )
        return $rs->fields['UID'];
    
    return false;
}

$smarty->assign('videos', $videos);
$smarty->assign('total_videos', $total_videos);
$smarty->assign('paging', $paging);
$smarty->assign('page', $page);
$smarty->assign('channels', $categorys);

?>
