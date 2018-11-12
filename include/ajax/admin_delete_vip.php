<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_admin.php';

$response = array('status' => 0, 'msg' => '');


$filter  = new VFilter();
$id     = $filter->get('id', 'INTEGER');

$sql = "DELETE FROM vip WHERE id = '" .mysql_real_escape_string($id). "' LIMIT 1";
$conn->execute($sql);
if ( $conn->Affected_Rows() ) {
    $response['status'] = 1;
}	

echo json_encode($response);
die();
?>
