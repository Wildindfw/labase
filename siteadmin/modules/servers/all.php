<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();

$query      = constructQuery();
$sql        = $query['select'];
$rs         = $conn->execute($sql);
$servers    = $rs->getrows();

function constructQuery()
{
    global $smarty;

    $query              = array();
    $query_select       = "SELECT * FROM servers";
    $query_count        = "SELECT count(server_id) AS total_servers FROM servers";
    $query_add          = NULL;
    $option_orig        = array('sort' => 'server_id', 'order' => 'DESC');
	
	$all   = (isset($_GET['all'])) ? intval($_GET['all']) : 0;
	if ($all == 1) {
		unset($_SESSION['search_servers_option']);
	}	
	
    $option             = ( isset($_SESSION['search_servers_option']) ) ? $_SESSION['search_servers_option'] : $option_orig;
    
    if ( isset($_POST['search_servers']) ) {
        $option['sort']     = trim($_POST['sort']);
        $option['order']    = trim($_POST['order']);      
        $_SESSION['search_servers_option'] = $option;
    }
    $query_add = " ORDER BY " .$option['sort']. " " .$option['order'];    
    $query['select']    = $query_select . $query_add;
    $query['count']     = $query_count . $query_add;
    
    $smarty->assign('option', $option);
    
    return $query;
}

$smarty->assign('servers', $servers);
?>
