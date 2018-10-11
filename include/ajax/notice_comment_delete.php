<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/dbconn.php';

$data   = array('msg' => '', 'status' => 0, 'debug' => '');
if ( isset($_POST['parent_id']) && isset($_POST['comment_id']) ) {
    if ( isset($_SESSION['uid']) ) {
        $filter     = new VFilter();
        $uid        = intval($_SESSION['uid']);
        $cid        = $filter->get('comment_id', 'INTEGER');
        $nid        = $filter->get('parent_id', 'INTEGER');
        $sql        = "DELETE FROM notice_comments WHERE UID = " .$uid. " AND NID = " .$nid. " AND CID = " .$cid. " LIMIT 1";
        $data['debug'] = $sql;
        $conn->execute($sql);
        if ( $conn->Affected_Rows() == 1 ) {
			$sql = "UPDATE notice SET total_comments = total_comments-1 WHERE NID = ".$nid." LIMIT 1";
			$conn->execute($sql);
            $data['status'] = 1;
			$data['msg'] = $lang['ajax.comment_delete_success'];
        } else { 
            $data['msg']    = $lang['ajax.comment_delete_failed'];  
        }
    } else {
        $data['msg'] = $lang['ajax.comment_delete_login'];
    }
} else {
    $data['msg'] = 'Invalid request!?';
}

echo json_encode($data);
die();
?>
