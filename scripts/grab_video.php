<?php
define('_VALID', 1);
define('_ENTER', true);
define('_CLI', true);


// Argvs
$vid = (int) $_SERVER['argv'][1];
$vurl = urldecode($_SERVER['argv'][2]);
$vdo_path = $_SERVER['argv'][3];
$vdoname = $_SERVER['argv'][4];

// Required
$basedir = dirname(dirname(__FILE__));
require $basedir. '/include/config.php';
require $basedir. '/classes/curl.class.php';
require $basedir. '/include/function_video.php';
require $basedir. '/include/function_conversions.php';


echo "\n".$nl."Argv\n".$nl;
echo "Parameters:\n";
echo "vdoname: $vdoname\n";
echo "vid: $vid\n";
echo "vdo_path: $vdo_path\n\n";
echo "vurl: $vurl\n\n";

// Execute
$curl           = new VCurl();

if ( !$curl->saveToFile($vurl, $vdo_path) ) {
	$sql        = "DELETE FROM video WHERE VID = " .$vid. " LIMIT 1";
	$conn->execute($sql);
	echo "Failed to download video file!\n\n";
} else {
	function run_in_background($Command, $Priority = 0) {
		if($Priority) $PID = shell_exec("nohup nice -n $Priority $Command 2> /dev/null & echo $!");
		else $PID = shell_exec("nohup $Command 2> /dev/null & echo $!");
		return($PID);
	}
	exec($config['mplayer']. ' -vo null -ao null -frames 0 -identify "' .$vdo_path. '"', $p);
	while(list($k,$v)=each($p)){
		if (preg_match("/^ID_.+\=.+/", $v)){
			$lx = explode("=", $v);
			$vidinfo[$lx[0]] = $lx[1];
		}
	}
	
	$flvdoname = $vid.'.flv';
	$video_id = $vid;
	$duration = $vidinfo['ID_LENGTH'];
	$height = $vidinfo['ID_VIDEO_HEIGHT'];
	$width = $vidinfo['ID_VIDEO_WIDTH'];
	$fps = $vidinfo['ID_VIDEO_FPS'];
	$id_video_format = $vidinfo['ID_VIDEO_FORMAT'];
	$cgi = ( strpos(php_sapi_name(), 'cgi') ) ? 'env -i ' : NULL;

	$cmd = $cgi.$config['phppath']
	." ".$config['BASE_DIR']."/scripts/convert_videos.php"
	." ".$vdoname
	." ".$video_id
	." ".$vdo_path
	."";                        
	echo "CMD: $cmd\n\n";
	log_conversion($config['LOG_DIR']. '/' .$video_id. '.log', $cmd);
	$lg = $config['LOG_DIR']. '/' .$video_id. '.log2';
	//convert
	run_in_background($cmd.' > '.$lg);
	$add  = NULL;
	$size = filesize($vdo_path);
}

if (!$errors) {
	$vkey        = substr(md5($vid),11,20);
	$sql         = "UPDATE video SET duration = '" .mysql_real_escape_string($duration). "', vkey = '" .$vkey. "',
					vdoname = '" .mysql_real_escape_string($vdoname). "', flvdoname = '" .mysql_real_escape_string($flvdoname). "',
					space = " .$size. ", active = '0'".$sql_add.$add."
					WHERE VID = " .intval($vid). " LIMIT 1";
	$conn->execute($sql);
	$sql         = "UPDATE channel SET total_videos = total_videos+1 WHERE CHID = " .$category. " LIMIT 1";
	$conn->execute($sql);
	$sql		 = "UPDATE signup SET total_videos = total_videos+1 WHERE UID = ".$uid." LIMIT 1";
	$conn->execute($sql);
	echo "Video was successfully added!\n\n";
}

exit();
?>
