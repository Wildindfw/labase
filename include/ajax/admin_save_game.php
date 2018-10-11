<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$data = (array) json_decode(stripslashes($_POST['data']));

$gid            = trim($data['id']);
$title          = trim($data['title']);
$tags           = trim($data['tags']);
$category       = trim($data['category']);
$type           = trim($data['type']);
$be_commented   = trim($data['be_commented']);
$be_rated       = trim($data['be_rated']);
$embed          = trim($data['embed']);      
$likes          = trim($data['likes']);
$dislikes       = trim($data['dislikes']);
$viewnumber     = trim($data['viewnumber']);
$active         = trim($data['active']);

settype($gid, 'integer');
settype($viewnumber, 'integer');
settype($likes, 'integer');
settype($dislikes, 'integer');
settype($category, 'integer');
if ( $likes != 0 || $dislikes !=0)
	$rate = round(($likes * 100)/($likes + $dislikes));
else
	$rate = 0;

$sql = "UPDATE game SET title = '" .mysql_real_escape_string($title). "', 
						tags = '" .mysql_real_escape_string($tags). "', category = '" .$category. "', type = '" .mysql_real_escape_string($type). "',
						be_commented = '" .mysql_real_escape_string($be_commented). "',
						be_rated = '" .mysql_real_escape_string($be_rated). "', 
						likes = '" .mysql_real_escape_string($likes). "', dislikes = '" .mysql_real_escape_string($dislikes). "', 
						rate = '" .mysql_real_escape_string($rate). "', total_plays = '" .mysql_real_escape_string($viewnumber). "',
						status  = '" .mysql_real_escape_string($active). "' 
		WHERE GID = '" .mysql_real_escape_string($gid). "' LIMIT 1";
$conn->execute($sql);
$response['status'] = 1;

echo json_encode($response);
die();
?>
