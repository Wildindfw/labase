<?php
define('_VALID', 1);
define('_ENTER', true);
define('_CLI', true);


// Argvs
$video_name = $_SERVER['argv'][1];
$vid = (int) $_SERVER['argv'][2];
$video_path = $_SERVER['argv'][3];
$video_delogo_param = null;
if(isset($_SERVER['argv'][4])){
	$video_delogo = $_SERVER['argv'][4];
}
$thumb_img = '';
if(isset($_SERVER['argv'][5])){
	$thumb_img = $_SERVER['argv'][5];
}

// Required
$basedir = dirname(dirname(__FILE__));
require $basedir. '/include/config.php';
require $basedir. '/include/function_video.php';
require $basedir. '/include/function_conversion.php';
require $basedir. '/include/function_server.php';
$vi = array();
$video_info = array();
$nl = "=========================================================\n";

echo "\n".$nl."Video Details:\n".$nl;
echo "\n".$nl."-----------------:\n".$nl;
echo "Parameters:\n";
echo "Video Name: $video_name\n";
echo "Vidoe ID: $vid\n";
echo "Video Path: $video_path\n\n";


// Error Checks
if (!preg_match("/^[0-9]{1,5}\.[a-z0-9]{2,4}$/i", $video_name)) {
	echo "Video Name: $video_name is invalid. Err #1. Exiting ..."; exit();
} else {
	$ffp_data = get_ffprobe_data($video_path);
	$video_info = ffpInfo($ffp_data);
}
if($video_delogo == 'true'){
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log',"检测到有水印命令，开始去除水印程序");
	//1024*576
	$x = intval($video_info['width']*0.86);
	$y = intval($video_info['height']*0.86);
	$w = intval($video_info['width']*0.13);
	$h = intval($video_info['height']*0.123);
	$param = "delogo=x=$x:y=$y:w=$w:h=$h";
	//$param = "delogo=x=880:y=500:w=138:h=66";
	delogo_video($vid,$param,$video_name);
}

// Get Encoder
$encodings = getEncodings();
foreach($encodings as $encoding) {
	
	convert($encoding, $vid, $video_name, $video_info);
	$output = $config['H264_DIR']."/".$vid."_".$encoding['label'].".".$encoding['format'];
	$flv = $config['FLVDO_DIR'].'/'.$vid.'.flv';
	$iphone = $config['IPHONE_DIR'].'/'.$vid.'.mp4';
	$hd = $output;
	$m3u8_path = convert_m3u8($output,$encoding,$vid);
	//转化成m3u8视频
	if($config['multi_server'] == '1'){
		$server = get_server();
		
		if(!empty($m3u8_path)){
			$log =  "start send m3u8 to server\n\n";
			log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
			
			$remote_md5_path = upload_m3u8_video($m3u8_path, $server['server_ip'], $server['ftp_username'], $server['ftp_password'], $server['ftp_root'],$vid);
			$log =  "send m3u8 to server ok!\n\n";
			log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
			//
			$format = "m3u8";
			
			$sql = "UPDATE video SET m3u8 = IF(m3u8 IS NULL, '".$encoding['height'].".".$remote_md5_path.".".$format."', CONCAT(m3u8, ',".$encoding['height'].".".$remote_md5_path.".".$format."')) WHERE VID = '".(int)$vid."'";
			
			executeQuery($sql);	
		}
		
		$sql = "UPDATE video SET server = '".$server['video_url']."' WHERE VID = '".(int)$vid."'";
		executeQuery($sql);
		$log =  "start send mp4 to server\n\n";
		log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
		upload_video($flv, $iphone, $hd, $server['server_ip'], $server['ftp_username'], $server['ftp_password'], $server['ftp_root']);
		$log =  "send mp4 to server ok\n\n";
		log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
		if(file_exists($flv) && filesize($flv) > 100){
			$skip = true;
		}
		@chmod($flv, 0777);
		@unlink($flv);
		@chmod($hd, 0777);
		@unlink($hd);
		@chmod($iphone, 0777);
		@unlink($iphone);	
	}	
}
$log =  "start shengcheng tu pian\n\n";
log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
postThumbs($vid,$video_path);
$pic_path =  $config['BASE_DIR'].DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'videos'.DIRECTORY_SEPARATOR.'tmb'.DIRECTORY_SEPARATOR.$vid.DIRECTORY_SEPARATOR;
	
//如果有图片需要下载的话
if($thumb_img && 0){
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "检测到缩略图需要下载");
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "开始下载缩略图".$thumb_img);
	$cmd = "wget -c '".$thumb_img."' -o ".$pic_path.'100.jpg';
	exec($cmd);
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "缩略图西在完成");
	$sql = "UPDATE video SET thumb = 100 WHERE VID = '".(int)$vid."'";
	executeQuery($sql);
}

$log =  "shengcheng tu pian wancheng \n\n";
log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);

if($config['multi_server'] == '1'){
	$server = get_server();
	$log =  "start shangchuan tu pian \n\n";
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
	upload_pics($pic_path, $server['server_ip'], $server['ftp_username'], $server['ftp_password'], $server['ftp_root']);
	$log =  "shangchuantu pian wancheng \n\n";
	log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
}
postConversion($vid,$video_path);
//删除原来的MP4文件
if(file_exists($config['VDO_DIR']."/".$video_name)){
	//unlink($config['VDO_DIR']."/".$video_name);
}
$log =  "zhuangma wancheng\n\n";
log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
// Display :: Encoder Core End
echo "\n<-- End of Script -->\n\n";
exit();
?>

