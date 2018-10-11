<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$filter  = new VFilter();
$gid     = $filter->get('blog_id', 'INTEGER');

$sql = "SELECT * from blog WHERE BID = '" .mysql_real_escape_string($gid). "' LIMIT 1";
$rs = $conn->execute($sql);
if ( $conn->Affected_Rows() == 1 ) {
	$blog = $rs->getrows();
	$blog = $blog[0];
	foreach ($blog as $key=>$value) {
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
