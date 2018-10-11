<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
//
define('_ENTER',true);
define('_VALID', true);

require 'include/config.php';


$url = $_POST['url'];
$title = $_POST['title'];
$keywords = $_POST['keywords'];
$category = $_POST['category'];
$thumburl = $_POST['thumburl'];

$sql = "SELECT * FROM grap_video_task WHERE url = '$url' LIMIT 1";

$rs  = $conn->execute($sql);
$tasks      = $rs->getrows();

if ( $conn->Affected_Rows() > 0 ) {
	$sql = "SELECT max(id),url FROM grap_video_task WHERE url = '$url' LIMIT 1";
	$rs  = $conn->execute($sql);
	$tasks = $rs->getrows();
	$task = $tasks[0];
	$lastest_url = $tasks[0]['url'];
    echo "error::::".$lastest_url;
	die();
}else{
	$sql        = "INSERT INTO `grap_video_task` (`id`, `status`, `url`, `title`, `md5`, `file_size`, `start_time`, `msg`, `keywords`, `thumburl`, `category`) VALUES (null, 1, '$url', '$title', '', '', '', '', '$keywords', '$thumburl', '$category');";
	$rs         = $conn->execute($sql);
	echo "ok::::";
}


?>
