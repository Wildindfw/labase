<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_admin.php';

$response = array('status' => 0, 'name' => '',  'errors' => '');

$data = (array) json_decode(stripslashes($_POST['data']));

$id      = trim($data['id']);
$name    = trim($data['name']);
$cost    = trim($data['cost']);
$describe    = trim($data['describe']);

settype($id, 'integer');

if ( empty($id) ) {
    $response['errors'] = 'ID不能为空!';
}else if ( empty($name) ) {
    $response['errors'] = '等级名称不能为空!';
}else if ( empty($cost) ) {
    $response['errors'] = '等级价格不能为空!';
}else if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $cost)){
    $response['errors'] = '等级价格只能是整数或小数二位!';   
}else{
    
    $sql = "UPDATE vip SET name = '" .mysql_real_escape_string($name). "', cost = '" .mysql_real_escape_string($cost). "', vip.describe = '" .mysql_real_escape_string($describe). "' WHERE id = '" .mysql_real_escape_string($id). "' LIMIT 1";
    
    
    $conn->execute($sql);
    
    $response['status'] = 1;
    
    $response['name'] = $name;
    $response['cost'] = $cost;
    $response['describe'] = $describe;
}




echo json_encode($response);
die();
?>
