<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();
$server = array('url' => '', 'video_url' => '', 'server_ip' => '', 'ftp_username' => '', 'ftp_password' => '', 'ftp_root' => '');
if (isset($_POST['add_server'])) {
	require $config['BASE_DIR']. '/classes/filter.class.php';
    require $config['BASE_DIR']. '/classes/validation.class.php';
    $filter             = new VFilter();
    $valid              = new VValidation();
	$url				= $filter->get('url');
	$server_ip			= $filter->get('server_ip');
	$ftp_username		= $filter->get('ftp_username');
	$ftp_password		= $filter->get('ftp_password');
	$ftp_root			= $filter->get('ftp_root');
	$video_url			= $filter->get('video_url');
	
	if ($url == '') {
		$errors[] = 'Server url cannot be left blank!';
		$err['url'] = 1;
	}
	$server['url'] = $url;


	if ($video_url == '') {
		$errors[] = 'Server video url cannot be left blank!';
		$err['video_url'] = 1;		
	}
	$server['video_url'] = $video_url;

	
	if ($server_ip == '') {
		$errors[] = 'Server ip cannot be left blank!';
		$err['server_ip'] = 1;			
	}
	$server['server_ip'] = $server_ip;

	
	if ($ftp_username == '') {
		$errors[] = 'Server FTP username cannot be left blank!';
		$err['ftp_username'] = 1;			
	}
	$server['ftp_username'] = $ftp_username;
	
	if ($ftp_password == '') {
		$errors[] = 'Server FTP password cannot be left blank!';
		$err['ftp_password'] = 1;			
	}
	$server['ftp_password'] = $ftp_password;

	
	$server['ftp_root'] = $ftp_root;
	
	if (!$errors) {
		$sql = "INSERT INTO servers
		        SET url = '".mysql_real_escape_string($url)."',
				    video_url = '".mysql_real_escape_string($video_url)."',
				    server_ip = '".mysql_real_escape_string($server_ip)."',
					ftp_username = '".mysql_real_escape_string($ftp_username)."',
					ftp_password = '".mysql_real_escape_string($ftp_password)."',
					ftp_root = '".mysql_real_escape_string($ftp_root)."',
					status = '0'";
		$conn->execute($sql);
		$messages[] = 'Server was successfuly added. Please enable this server from the servers list!';
		$server['url'] = '';
		$server['video_url'] = '';
		$server['server_ip'] = '';
		$server['ftp_username'] = '';
		$server['ftp_password'] = '';
		$server['ftp_root'] = '';
	}	
}

$smarty->assign('server', $server);
?>
