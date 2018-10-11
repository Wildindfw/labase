<?php
/*VS Conversion Functions
|*| Convert H264
|*|-------------------------------------------------
|*/	

function scale ($iw, $ih, $rw, $rh) {
	if (($iw/$ih)<=($rw/$rh)) {
		$ow = $iw*$rh/$ih;
		$oh = $rh;
	} else {
		$oh = $ih*$rw/$iw;
		$ow = $rw;
	}
	$ow = floor($ow/2)*2;
	$oh = floor($oh/2)*2;
	$scale = "-vf scale=".$ow.":".$oh;
	return $scale;
}

function delogo_video($vid,$param,$video_name){
	global $config;
	$log = $config['LOG_DIR']. '/' .$vid. '.log';
	$ffmpeg_path = $config['ffmpeg'];
	$video_path = $config['VDO_DIR']."/".$video_name;
	$out_path = $config['VDO_DIR']."/".$vid."_nolog.mp4";;
	$cmd = $ffmpeg_path." -i ".$video_path." -vf ".$param." ".$out_path;
	log_conversion($log,"执行去除水印命令cmd:".$cmd);
	modproc($cmd);
	
	if(!file_exists($out_path)||filesize($out_path)<1000){
		log_conversion($log,"去除水印失败，退出程序");
		exit();
	}
	log_conversion($log,"执行去除水印命令成功完成");
	log_conversion($log,"删除原始文件");
	unlink($video_path);
	log_conversion($log,"重新命名新文件");
	rename($out_path,$video_path);	
}
function ratio($a, $b) {
    $gcd = function($a, $b) use (&$gcd) {
        return ($a % $b) ? $gcd($b, $a % $b) : $b;
    };
    $g = $gcd($a, $b);
    return $a/$g . ':' . $b/$g;
}

function get_mediainfo_data($videofile) {
	global $config;
	$varr = array();
	$output1 = array();
	$output2 = array();
	$media_general = $config['BASE_DIR']."/scripts/media_general.txt";
	$media_video = $config['BASE_DIR']."/scripts/media_video.txt";
	if (!preg_match("/mediainfo$/is", $config['mediainfo'])){
		$error = 'Mediainfo error';
	}else{
		$command1 = $config['mediainfo']." --Inform=file://".$media_general." ".$videofile;
		exec($command1,$output1);
		$command2 = $config['mediainfo']." --Inform=file://".$media_video." ".$videofile;
		exec($command2,$output2);
		$error = '';
	}
	$varr['error'] = $error;
	$varr['media_gen_cmd'] = $command1;
	$varr['media_vid_cmd'] = $command2;
	$varr['media_gen_out'] = $output1;
	$varr['media_vid_out'] = $output2;
	return $varr;
}

function videoInfo($vi) {
	foreach($vi['media_gen_out'] as $line){
		if (preg_match("/^(General_|Video_).+?\=.*/", $line)){
			$line_arr = explode("=", $line);
			$video_info[$line_arr[0]] = $line_arr[1];
		}
	}
	foreach($vi['media_vid_out'] as $line){
		if (preg_match("/^(General_|Video_).+?\=.*/", $line)){
			$line_arr = explode("=", $line);
			$video_info[$line_arr[0]] = $line_arr[1];
		}
	}	
	echo "\n".$nl."Media Descriptors Commands\n".$nl;
	echo "Comand 1: ".$vi['media_gen_cmd']."\n";
	echo "Comand 2: ".$vi['media_vid_cmd']."\n";
	echo "\n".$nl."Media Info\n".$nl;
	foreach ($video_info as $key => $val){
		echo "\$video_info['".$key."'] = '".$val."';\n";
	}
	return $video_info;
}

function get_ffprobe_data($videofile) {
	global $config;
	$varr = array();
	$output1 = array();
	$output2 = array();

	$command1 = $config['ffprobe']." -v error -select_streams v:0 -show_entries stream=codec_long_name,codec_name,width,height,display_aspect_ratio -of default=noprint_wrappers=1 ".$videofile."";	
	exec($command1,$output1);

	$command2 = $config['ffprobe']." -v error -show_entries format=filename,format_name,duration,size -of default=noprint_wrappers=1 ".$videofile."";	
	exec($command2,$output2);

	$varr['stream'] = $output1;
	$varr['format'] = $output2;	
	$varr['ffp_cmd1'] = $command1;
	$varr['ffp_cmd2'] = $command2;	
	return $varr;
}

function ffpInfo($vi) {
	foreach($vi['stream'] as $line){
		$line_arr = explode("=", $line);
		switch ($line_arr[0]) {
			case 'width':
				$video_info[$line_arr[0]] = intval($line_arr[1]);
				break;
			case 'height':
				$video_info[$line_arr[0]] = intval($line_arr[1]);
				break;
			default:
				$video_info[$line_arr[0]] = $line_arr[1];
		}
		if ($video_info['display_aspect_ratio'] == '0:1') {
			$video_info['display_aspect_ratio'] = ratio($video_info['width'], $video_info['height']);
		}
		
	}
	foreach($vi['format'] as $line){
		$line_arr = explode("=", $line);
		switch ($line_arr[0]) {
			case 'duration':
				$video_info[$line_arr[0]] = floatval($line_arr[1]);
				break;
			case 'size':
				$video_info[$line_arr[0]] = intval($line_arr[1]);
				break;
			default:
				$video_info[$line_arr[0]] = $line_arr[1];
		}
		$filename_arr = explode(".",$video_info['filename']);
		$video_info['file_extension'] = end($filename_arr);
		
	}	
	echo "\n".$nl."FFProbe Command\n".$nl;
	echo "Comand 1: ".$vi['ffp_cmd1']."\n";
	echo "Comand 2: ".$vi['ffp_cmd2']."\n";	

	echo "\n".$nl."FFProbe Info\n".$nl;
	foreach ($video_info as $key => $val){
		echo "\$video_info['".$key."'] = '".$val."';\n";
	}
	return $video_info;
}

function print_log($txt) {
	global $config;
	if ($config['log_conversion']){
		print ($txt);
	}
}

function modproc($cmd) {
	$cmd = str_replace(" ;", " 2>&1 ;", $cmd)." 2>&1";
	$nl = "=========================================================\n";
	echo "\n".$nl."Command:\n".$nl.$cmd."\n\n";
	exec($cmd,$out);
	foreach($out as $outd){
		$outs .= $outd."\n";
	}
	echo "Output:\n".$outs."\n\n";
}

function getEncodings() {
	global $config, $conn;
	$sql = "SELECT * FROM encoding WHERE status ='1' ORDER BY height DESC";
	$rs = $conn->execute($sql);
    $encodings = $rs->getrows();
	end($encodings);
	$lastkey = key($encodings);
	$encodings[$lastkey]['lq'] = true;
	return $encodings;
}
function write_m3u8($file_url,$ad_url,$ad_length){
		$str = file_get_contents($file_url);
		$tags = explode("#EXTINF",$str);
		$old_header = $tags[0];
		$ad_length = $ad_length;
		$new_header = $old_header.$ad_url;
		$new_str = str_replace($old_header,$new_header,$str);
		$myfile = file_put_contents($file_url,$new_str);
}
function convert_m3u8($video_path,$e,$vid){
	global $config;
	if(!file_exists($video_path)){
		$log = $config['LOG_DIR']. '/' .$vid. '.log';
		log_conversion($log,"start convert m3u8,but not find file,return!\n");
		return '';
	}else{
		$log = $config['LOG_DIR']. '/' .$vid. '.log';
		log_conversion($log,"start convert m3u8\n");
	}
	
	//创建TS目录
	$md5_path = md5(time().mt_rand(0,10000));
	$m3u8_storge_path = $config['M3U8_DIR'].DIRECTORY_SEPARATOR.$md5_path.DIRECTORY_SEPARATOR;
	@mkdir($m3u8_storge_path);
	
	//转化成ts文件
	$ts_name = 'mp4.ts';
	$ts_output_path = $m3u8_storge_path.$ts_name;
	$ffmpeg_path = $config['ffmpeg'];
	$cmd = $ffmpeg_path.' -y -i '.$video_path.' -vcodec copy -acodec copy -vbsf h264_mp4toannexb '.$ts_output_path;	
	exec($cmd,$res);
	log_conversion($log,"start exec cmd:".$cmd);
	
	//转化为M3U8文件
	$m3u8_path = $m3u8_storge_path.'index.m3u8';
	$cmd = $ffmpeg_path.' -i '.$ts_output_path.' -c copy -map 0 -f segment -segment_list '.$m3u8_path.' -segment_time 3 '.$m3u8_storge_path.'index-%03d.ts';
	exec($cmd,$res);
	log_conversion($log,"start exec cmd:".$cmd);
	
	//插入广告
	write_m3u8($m3u8_path,$config['ADS_URL'],$config['ADVS_LENGTH']);
	
	//删除ts.mp4
	unlink($ts_output_path);
	return $m3u8_storge_path;
}

function convert ($e, $vid, $video_name, $video_info) {
	global $config;
	$nl = "=========================================================\n";

	// Output :: Vars
	echo "\n".$nl."Output - Conversion Config:\n".$nl;
	echo "Label: ".$e['label']."\n";
	echo "Resolution: ".$e['width'].'x'.$e['height']."\n";
	echo "Constant Rate Factor: ".$e['crf']."\n";
	echo "Preset: ".$e['preset']."\n";
	echo "iOS Compatability: ".$e['ios']."\n";
	echo "Fast Start: ".$e['faststart']."\n";	
	echo "Copy Only: ".$e['copyonly']."\n";

	if (($e['height'] <= $video_info['height'] || $e['width'] <= $video_info['width']) || $e[lq]) {
		// Source Video Path info
		$src = $config['VDO_DIR']."/".$video_name;

		// HD Paths info		
		$output = $config['H264_DIR']."/".$vid."_".$e['label'].".".$e['format'];
		
		if ($e['faststart']) {
			$faststart = "-movflags +faststart";
		} else {
			$faststart = "";			
		}
		if ($e['copyonly'] && ($e['height'] == $video_info['height'] || $e['width'] == $video_info['width']) && $video_info['file_extension'] == "mp4" && strpos($video_info['format_name'], 'mp4') !== false && $video_info['codec_name'] == "h264" && strpos($video_info['codec_long_name'], 'MPEG-4') !== false && strpos($video_info['codec_long_name'], 'AVC') !== false) {
			if (@copy($src,$output)) {
				log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "COPY ONLY: Output resolution/format is the same with the input resloution");
				echo "\n"."COPY ONLY: Output resolution/format is the same with the input resloution/format!\n\n";
			}	
		} else {
			if ($e[lq] && ($e['height'] > $video_info['height'] || $e['width'] > $video_info['width'])) {
				$scale ="";
				if ($e['height'] >= 480) {
					$e['label'] = 'HD';
				} else {
					$e['label'] = 'SD';
				}
			} else {
				$scale = "-vf scale=\"'if(gt(a,4/3),".$e['width'].",-1)':'if(gt(a,4/3),-1,".$e['height'].")'\"";
			}
			$scale = scale($video_info['width'], $video_info['height'], $e['width'], $e['height']);
			$cmd = $config['ffmpeg']." -i ".$src." -c:v libx264 -preset ".$e['preset']." -crf ".$e['crf']." ".$scale." ".$e['ios']." ".$faststart." ".$output."";
			$output = $config['H264_DIR']."/".$vid."_".$e['label'].".".$e['format'];
		//	$cmd = $config['ffmpeg']." -i ".$src.' -s '.$scale." ".$output."";
			log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $cmd.'\n\n');
			modproc($cmd);
		}
		if (file_exists($output) && filesize($output) > 100) {
			log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "inster table formats,lformats\n\n");
			$sql = "UPDATE video SET formats = IF(formats IS NULL, '".$e['height'].".".$e['label'].".".$e['format']."', CONCAT(formats, ',".$e['height'].".".$e['label'].".".$e['format']."')) WHERE VID = '".(int)$vid."'";
			executeQuery($sql);
			echo "\n".$nl."SQL:\n".$nl.$sql."\n\n";
			$sql = "UPDATE video SET lformats = IF(lformats IS NULL, '".$e['label']."', CONCAT(lformats, ', ".$e['label']."')) WHERE VID = '".(int)$vid."'";
			executeQuery($sql);
			echo "\n".$nl."SQL:\n".$nl.$sql."\n\n";			
		} else {
			log_conversion($config['LOG_DIR']. '/' .$vid. '.log', "output file is not well delelte\n\n");
			@chmod($output, 0777);
			@unlink($output);			
			$scale = scale($video_info['width'], $video_info['height'], $e['width'], $e['height']);
			echo "\n"."Retrying using fixed scale: ".$scale."\n";
			$cmd = $config['ffmpeg']." -i ".$src." -y ".$output."";
			if (file_exists($output) && filesize($output) > 100) {
				$sql = "UPDATE video SET formats = IF(formats IS NULL, '".$e['height'].".".$e['label'].".".$e['format']."', CONCAT(formats, ',".$e['height'].".".$e['label'].".".$e['format']."')) WHERE VID = '".(int)$vid."'";
				executeQuery($sql);
				echo "\n".$nl."SQL:\n".$nl.$sql."\n\n";
				$sql = "UPDATE video SET lformats = IF(lformats IS NULL, '".$e['label']."', CONCAT(lformats, ', ".$e['label']."')) WHERE VID = '".(int)$vid."'";
				executeQuery($sql);
				echo "\n".$nl."SQL:\n".$nl.$sql."\n\n";				
			}
		}
	} else {
		$log =  "\n"."SKIP CONVERSION: Output resolution is higher than the input resloution!\n\n";
		log_conversion($config['LOG_DIR']. '/' .$vid. '.log', $log);
	}
}

function postThumbs($vid, $src) {
	global $config;
	
	$sql     = "SELECT formats, server FROM video WHERE VID = '" .$vid. "' LIMIT 1";
	$rs      = selectQuery($sql);
    $formats = $rs['formats'];
    $server  = $rs['server'];

	$formats = explode(',', $formats);
	foreach ($formats as $format) {
		 unset($f);
		 $f    = explode('.', $format);
		 $vf[] = $config['H264_DIR'].'/'.$vid."_".$f[1].".".$f[2];
	}
	if ($server != '') {
		foreach ($formats as $format) {
			 unset($f);
			 $f    = explode('.', $format);
			 $vfs[] = $server.'/h264/'.$vid."_".$f[1].".".$f[2];
		}		
	}
	foreach ($vf as $file) {
		if (file_exists($file) && filesize($file) > 100) {
			echo "\n"."Extracting thumbnails: ".$file."\n\n";
			extract_video_thumbs($file, $vid, 'all', $config['thumbnail_remove_bb'], $config['thumbnail_keep_ar']);
			return;
		}
	}
	foreach ($vfs as $file) {
		if (file_url_exists($file)) {
			echo "\n"."Extracting thumbnails: ".$file."\n\n";
			extract_video_thumbs($file, $vid, 'all', $config['thumbnail_remove_bb'], $config['thumbnail_keep_ar']);			
			return;
		}
	}
	if (file_exists($src) && filesize($src) > 100) {	
		echo "\n"."Extracting thumbnails: ".$src."\n\n";
		extract_video_thumbs($src, $vid, 'all', $config['thumbnail_remove_bb'], $config['thumbnail_keep_ar']);
		return;		
	}
}

function postConversion($vid,$src) {
	global $config;

	$nl = "=========================================================\n";

	$sql  	     = "SELECT formats FROM video WHERE VID = '" .$vid. "' LIMIT 1";
	$rs 	     = selectQuery($sql);
    $formats     = explode(',', $rs['formats']);
	
	$hd          = 0;	
	if ($config['approve'] == '0' && $rs['formats']!='') {
		$active = 1;
	} else {
		$active = 0;
	}

	$sd_f        = explode('.', end($formats));
	$sd_vf       = $config['H264_DIR'].'/'.$vid."_".$sd_f[1].".".$sd_f[2];
	$sd_ffp_data = get_ffprobe_data($sd_vf);
	$sd_vi       = ffpInfo($sd_ffp_data);
	
	if (intval($sd_f[0]) >= 480) {
		$hd = 1;
	}
	$sql = 	"UPDATE video SET 
			active = '".$active."', 
			duration = '".$sd_vi['duration']."',
			width_sd = '".$sd_vi['width']."', 
			height_sd = '".$sd_vi['height']."', 
			aspect_sd = '".$sd_vi['display_aspect_ratio']."' 
			WHERE VID = '".(int)$vid."' LIMIT 1";
			
	if (count($formats)>1) {
		$hd_f    = explode('.', $formats[0]);
		$hd_vf = $config['H264_DIR'].'/'.$vid."_".$hd_f[1].".".$hd_f[2];
		$hd_ffp_data = get_ffprobe_data($hd_vf);
		$hd_vi = ffpInfo($hd_ffp_data);
		if (intval($hd_f[0]) >= 480) {
			$hd = 1;
			$sql = 	"UPDATE video SET 
					active = '".$active."', 
					duration = '".$hd_vi['duration']."', 
					width_hd = '".$hd_vi['width']."', 
					height_hd = '".$hd_vi['height']."', 
					aspect_hd = '".$hd_vi['display_aspect_ratio']."', 
					width_sd = '".$sd_vi['width']."', 
					height_sd = '".$sd_vi['height']."', 
					aspect_sd = '".$sd_vi['display_aspect_ratio']."', 
					hd = '".$hd."' 
					WHERE VID = '".(int)$vid."' LIMIT 1";			
		}
	} else {
		if ($hd == 1) {
			$sql = 	"UPDATE video SET 
					active = '".$active."', 
					duration = '".$sd_vi['duration']."', 
					width_hd = '".$sd_vi['width']."', 
					height_hd = '".$sd_vi['height']."', 
					aspect_hd = '".$sd_vi['display_aspect_ratio']."', 
					width_sd = '".$sd_vi['width']."', 
					height_sd = '".$sd_vi['height']."', 
					aspect_sd = '".$sd_vi['display_aspect_ratio']."', 
					hd = '".$hd."' 
					WHERE VID = '".(int)$vid."' LIMIT 1";				
		}
	}
	
	executeQuery($sql);
	echo "\n".$nl."SQL:\n".$nl.$sql."\n\n";
	
	// Delete original video?
	if ($config['del_original_video'] == 1) {
	//	@chmod($src, 0777);
	//	@unlink($src);
	}	
}

/*|*****************************************
|*| Function :: DB SELECTOR
|*|*****************************************
|*/ function executeQuery($query) {
		global $config;
		$link = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass'], true);
		if($link){	
			mysql_select_db($config['db_name']);
			$result = mysql_query($query);
			if($result){
				$id = mysql_insert_id();
			}
			$err = mysql_error();
			mysql_close($link);
		}else{
			$err = 'Could not connect to '.$dbs.': ' . mysql_error();
		}
		$result = (intval($id) > 0) ? $id : $result;
		$result = ($err != "") ? "Sql Error :: ".$err."<br/>" : $result;
		return $result;
	}
	
 function selectQuery($query) {
		global $config;
		$link = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass'], true);
		if($link){	
			mysql_select_db($config['db_name']);
			$result = mysql_fetch_array(mysql_query($query));
			$err = mysql_error();
			mysql_close($link);
		} else {
			$err = 'Could not connect to '.$dbs.': ' . mysql_error();
		}
		$result = ($err != "") ? "Sql Error :: ".$err."<br/>" : $result;
		return $result;
	}	
?>
