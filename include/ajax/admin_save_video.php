<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_video.php';

function nl2br2($string) { 
	$string = str_replace(array("\\r\\n", "\\r", "\\n"), "\n", $string);
	return $string; 
}

$response = array('status' => 0);

$data = (array) json_decode(stripslashes($_POST['data']));

$vid            = trim($data['id']);
$title          = trim($data['title']);
$description    = trim($data['description']);
$keyword        = trim($data['tags']);
$channel        = trim($data['category']);
$type           = trim($data['type']);
$featured       = trim($data['featured']);
$be_comment     = trim($data['be_comment']);
$be_rated       = trim($data['be_rated']);
$embed          = trim($data['embed']);      
$likes          = trim($data['likes']);
$dislikes       = trim($data['dislikes']);
$viewnumber     = trim($data['viewnumber']);
$active         = trim($data['active']);
$server			= trim($data['server']);

settype($vid, 'integer');
settype($viewnumber, 'integer');
settype($likes, 'integer');
settype($dislikes, 'integer');
settype($channel, 'integer');
if ( $likes != 0 || $dislikes !=0)
	$rate = round(($likes * 100)/($likes + $dislikes));
else
	$rate = 0;

$sql = "UPDATE video SET title = '" .mysql_real_escape_string($title). "', description = '" .nl2br2(mysql_real_escape_string($description)). "',
						 keyword = '" .mysql_real_escape_string($keyword). "', channel = '" .$channel. "', type = '" .mysql_real_escape_string($type). "',
						 featured = '" .mysql_real_escape_string($featured). "', be_comment = '" .mysql_real_escape_string($be_comment). "',
						 be_rated = '" .mysql_real_escape_string($be_rated). "', embed = '" .mysql_real_escape_string($embed). "',
						 likes = '" .mysql_real_escape_string($likes). "', dislikes = '" .mysql_real_escape_string($dislikes). "', 
						 rate = '" .mysql_real_escape_string($rate). "', viewnumber = '" .mysql_real_escape_string($viewnumber). "',
						 active  = '" .mysql_real_escape_string($active). "', server = '".mysql_real_escape_string($server)."'
		WHERE VID = '" .mysql_real_escape_string($vid). "' LIMIT 1";
$conn->execute($sql);
$response['status'] = 1;

echo json_encode($response);
die();
?>
