<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$filter  = new VFilter();
$gid     = $filter->get('game_id', 'INTEGER');
$gstatus = $filter->get('game_status', 'INTEGER');

if ($gstatus) {
	$sql = "UPDATE game SET status = '0' WHERE GID = '" .mysql_real_escape_string($gid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {	
		$response['status'] = 1;	
	}
} else {
	$sql = "UPDATE game SET status = '1' WHERE GID = '" .mysql_real_escape_string($gid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {
		//send_game_approve_email($gid);
		$response['status'] = 1;
	}
}

echo json_encode($response);
die();
?>
