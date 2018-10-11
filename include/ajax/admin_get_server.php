<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$filter  = new VFilter();
$sid     = $filter->get('server_id', 'INTEGER');

$sql = "SELECT * from servers WHERE server_id = '" .mysql_real_escape_string($sid). "' LIMIT 1";
$rs = $conn->execute($sql);
if ( $conn->Affected_Rows() == 1 ) {
	$server = $rs->getrows();
	$server = $server[0];
	foreach ($server as $key=>$value) {
		if ($key == 'status') {
			$key = 'active';
		}		
		$response[$key] = $value;
	}		
	$response['status'] = 1;
}

echo json_encode($response);
die();
?>
