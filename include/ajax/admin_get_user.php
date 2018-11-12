<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_video.php';

$response = array('status' => 0);

$filter  = new VFilter();
$uid     = $filter->get('user_id', 'INTEGER');

$sql = "SELECT * from signup WHERE UID = '" .mysql_real_escape_string($uid). "' LIMIT 1";
$rs = $conn->execute($sql);
if ( $conn->Affected_Rows() == 1 ) {
	$user = $rs->getrows();
	$user = $user[0];
	foreach ($user as $key=>$value) {	
		$response[$key] = $value;
	}	
	
	if($response['vip_deadline']){
	    $response['vip_deadline_format'] = date("Y-m-d",$response['vip_deadline']);
	}
	if($response['vip_start_time']){
	    $response['vip_start_time_format'] = date("Y-m-d",$response['vip_start_time']);
	}
	
	$response['status'] = 1;
}

echo json_encode($response);
die();
?>
