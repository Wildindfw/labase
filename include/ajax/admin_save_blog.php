<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

function nl2br2($string) { 
	$string = str_replace(array("\\r\\n", "\\r", "\\n"), "\n", $string);
	return $string; 
} 

$response = array('status' => 0);

$data = (array) json_decode(stripslashes($_POST['data']));

$bid            = trim($data['id']);
$title          = trim($data['title']);
$content        = trim($data['content']);
$viewnumber     = trim($data['viewnumber']);
$active         = trim($data['active']);

settype($bid, 'integer');
settype($viewnumber, 'integer');

$sql = "UPDATE blog SET title = '" .mysql_real_escape_string($title). "', 
						content = '" .nl2br2(mysql_real_escape_string($content)). "', 
						total_views = '" .mysql_real_escape_string($viewnumber). "', 
						status  = '" .mysql_real_escape_string($active). "' 
		WHERE BID = '" .mysql_real_escape_string($bid). "' LIMIT 1";
$conn->execute($sql);
$response['status'] = 1;

echo json_encode($response);
die();
?>
