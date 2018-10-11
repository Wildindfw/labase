<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_video.php';

$response = array('status' => 0);

$filter  = new VFilter();
$vid     = $filter->get('video_id', 'INTEGER');

$sql = "SELECT * from video WHERE VID = '" .mysql_real_escape_string($vid). "' LIMIT 1";
$rs = $conn->execute($sql);
if ( $conn->Affected_Rows() == 1 ) {
	$video = $rs->getrows();
	$video = $video[0];
	foreach ($video as $key=>$value) {
		$response[$key] = $value;
	}		
	$response['status'] = 1;
}

echo json_encode($response);
die();
?>
