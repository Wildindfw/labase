<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$response = array('status' => 0);

$data = (array) json_decode(stripslashes($_POST['data']));

$aid            = trim($data['id']);
$name           = trim($data['name']);
$tags           = trim($data['tags']);
$category       = trim($data['category']);
$type           = trim($data['type']);
$likes          = trim($data['likes']);
$dislikes       = trim($data['dislikes']);
$total_views    = trim($data['viewnumber']);
$status         = trim($data['active']);

settype($aid, 'integer');
settype($total_views, 'integer');
settype($likes, 'integer');
settype($dislikes, 'integer');
settype($category, 'integer');
if ( $likes != 0 || $dislikes !=0)
	$rate = round(($likes * 100)/($likes + $dislikes));
else
	$rate = 0;

$sql   = "UPDATE albums SET name = '" .mysql_real_escape_string($name). "', tags = '" .mysql_real_escape_string($tags). "', 
							category = '" .$category. "', type = '" .mysql_real_escape_string($type). "', 
							likes = '" .mysql_real_escape_string($likes). "', dislikes = '" .mysql_real_escape_string($dislikes). "', 
							rate = '" .mysql_real_escape_string($rate). "', total_views = '" .mysql_real_escape_string($total_views). "',
							status  = '" .mysql_real_escape_string($status). "' 
		  WHERE AID = '" .mysql_real_escape_string($aid). "' LIMIT 1";
$conn->execute($sql);
$response['status'] = 1;

echo json_encode($response);
die();
?>
