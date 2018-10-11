<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_editor.php';

$response = array('status' => 0);

$data = $_POST['data'];

$nid            = trim($data['id']);
$title          = trim($data['title']);
$content        = wysiwygColorToCSS(trim($data['content']));
$viewnumber     = trim($data['viewnumber']);
$active         = trim($data['active']);
$category       = trim($data['category']);

settype($nid, 'integer');
settype($viewnumber, 'integer');
settype($category, 'integer');

$sql = "UPDATE notice SET title = '" .mysql_real_escape_string($title). "', 
						content = '".mysql_real_escape_string($content). "', 
						total_views = '" .mysql_real_escape_string($viewnumber). "', 
						status  = '" .mysql_real_escape_string($active). "' 
		WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
$conn->execute($sql);

$sql = "SELECT category FROM notice WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
$rs = $conn->execute($sql);
$old_category = (int) $rs->fields['category'];

if ($old_category != $category) {
	$sql = "UPDATE notice SET category = '" .mysql_real_escape_string($category). "' 						
			WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
	$conn->execute($sql);
	$sql = "UPDATE notice_categories SET total_notices = total_notices-1 WHERE category_id = '" .mysql_real_escape_string($old_category). "' LIMIT 1";
	$conn->execute($sql);
	$sql = "UPDATE notice_categories SET total_notices = total_notices+1 WHERE category_id = '" .mysql_real_escape_string($category). "' LIMIT 1";
	$conn->execute($sql);	
}


$response['status'] = 1;

echo json_encode($response);
die();
?>
