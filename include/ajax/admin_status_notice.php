<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$filter  = new VFilter();
$nid     = $filter->get('notice_id', 'INTEGER');
$gstatus = $filter->get('notice_status', 'INTEGER');

if ($gstatus) {
	$sql = "UPDATE notice SET status = '0' WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {	
		$response['status'] = 1;	
	}
} else {
	$sql = "UPDATE notice SET status = '1' WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
	$conn->execute($sql);
	if ( $conn->Affected_Rows() == 1 ) {
		$response['status'] = 1;
	}
}

echo json_encode($response);
die();
?>
