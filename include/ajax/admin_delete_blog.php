<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';
require $config['BASE_DIR']. '/include/function_admin.php';

$response = array('status' => 0, 'msg' => '', 'debug' => '');

$filter     = new VFilter();
$bid        = $filter->get('blog_id', 'INTEGER');
deleteBlog( $bid );
$response['status'] = 1;

echo json_encode($response);
die();
?>
