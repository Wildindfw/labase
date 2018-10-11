<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_video.php';

$response = array('status' => 0);

$filter  = new VFilter();
$aid     = $filter->get('album_id', 'INTEGER');
$astatus = $filter->get('album_status', 'INTEGER');

if ($astatus) {
	$sql = "UPDATE albums SET status = '0' WHERE AID = '" .mysql_real_escape_string($aid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {	
		$response['status'] = 1;	
	}
} else {
	$sql = "UPDATE albums SET status = '1' WHERE AID = '" .mysql_real_escape_string($aid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {
		$response['status'] = 1;
	}
}

echo json_encode($response);
die();
?>
