<?php
/*
 
-- ----------------------------
-- Table structure for grap_video_task
-- ----------------------------
DROP TABLE IF EXISTS `grap_video_task`;
CREATE TABLE `grap_video_task`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(1) NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `title` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `md5` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_size` bigint(10) NULL DEFAULT NULL,
  `start_time` int(10) NULL DEFAULT NULL,
  `msg` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;
添加字段

alter table grap_video_task add COLUMN keywords varchar(500);
alter table grap_video_task add COLUMN thumburl varchar(500);
alter table grap_video_task add COLUMN category int(3);

-- ----------------------------
-- Records of grap_video_task
-- ----------------------------
INSERT INTO `` VALUES (3, 1, 'http://www.aotu47.com/file/17820/1/597bffbd7c8bc76df72a/1531099227/mp4/17820.mp4', '1', '076f4', 32495786, 1531143636, '请求超时');

SET FOREIGN_KEY_CHECKS = 1;*/
define('_VALID', 1);
define('_ENTER', true);
define('_CLI', true);
$basedir = dirname(dirname(__FILE__));
require $basedir. '/include/config.php';
require $basedir. '/classes/curl.class.php';
require $basedir. '/include/function_video.php';
 ini_set('memory_limit','-1');
$curl = new VCurl();
$max_allow_num = 1;
$time_out = 6000;
//判断当前执行的任务数量 status = 10;
$sql = "SELECT * FROM grap_video_task WHERE status = 10";
$rs  = $conn->execute($sql);
if ( $conn->Affected_Rows() >= $max_allow_num) {
	//
	$last_time = time()- $time_out;
	$sql = "UPDATE grap_video_task SET status = 6,msg='请求超时' WHERE status = 10 AND start_time<".$last_time;
	$rs  = $conn->execute($sql);
    exit();
}

$sql = "SELECT * FROM grap_video_task WHERE status = 1 LIMIT 1";
$rs  = $conn->execute($sql);
$tasks      = $rs->getrows();
if ( $conn->Affected_Rows() != 1 ) {
    
}else{
	//设置状态和开始时间
	$task = $tasks[0];
	$sql = "UPDATE grap_video_task set status=10,msg='正在执行任务中',start_time=".time()." WHERE id=".$task['id'];
	$rs  = $conn->execute($sql);

	$vurl = $task['url'];
	//判断url 是否符合格式
	if(empty($task['url']) ||substr(strrchr($vurl, '.'), 1)!='mp4'){
		$sql = "UPDATE grap_video_task set status=4,msg='文件后缀名错误' WHERE id=".$task['id'];
		$rs  = $conn->execute($sql);
		exit();
	}
	
	//下载文件
	$vdo_path = $config['VDO_DIR'].'/tmp.mp4';
	if (!$curl->saveToFile($vurl, $vdo_path)) {
		$sql = "UPDATE grap_video_task set status=3,msg='文件下载失败' WHERE id=".$task[id];
		$rs  = $conn->execute($sql);
		exit();		
	}else{
		$md5 = md5_file($vdo_path);
		$file_size = filesize($vdo_path);
		
		$sql = "UPDATE grap_video_task set msg='文件下载成功' WHERE id=".$task[id];
		$rs  = $conn->execute($sql);
		
		//判断文件是否已经下载过
		$sql = "SELECT * FROM grap_video_task WHERE md5 ='".$md5."' LIMIT 1";	
		$rs  = $conn->execute($sql);	
		if ( $conn->Affected_Rows() != 1 ) {
			//传入参数
			$title      = $task['title'];
			$keywords   = $task['keywords'];
			$paysite    = $task['title'];
			$category    = $task['category'];
			$space = $file_size;
			$privacy    = 'public';
			$anonymous  = 'yes';
			$description = $task['title'];
			
			$filename           = substr( $vdo_path, strrpos( $vdo_path, DIRECTORY_SEPARATOR)+1);
			$extension          = strtolower(substr($filename, strrpos($filename, '.')+1));
			$video['paysite']       = $paysite;
			$video['privacy']       = ( $privacy == 'private' ) ? 'private' : 'public';
			$video['anonymous']     = ( $anonymous == 'yes' ) ? 'yes' : 'no';
			$uid                    =  1;
			$sql        = "INSERT INTO video 
                       SET UID = " .$uid. ", title = '" .mysql_real_escape_string($title). "',
                           channel = " .$category. ", keyword = '" .mysql_real_escape_string($keywords). "',
						   description = '".mysql_real_escape_string($description)."', 
                           space = '" .$space. "', addtime = '" .time(). "', adddate = '" .date('Y-m-d'). "', vkey = '" .mt_rand(). "', 
                           type = '" .$video['privacy']. "', active = '2'";
			$conn->execute($sql);
			$video_id   = mysql_insert_id();
			
			$vkey       = substr(md5($video_id),11,20);
			$sql        = "UPDATE video SET vkey = '" .$vkey. "' WHERE VID = " .intval($video_id). " LIMIT 1";
			$conn->execute($sql);	
			
			$vdoname    = $video_id. '.' .$extension;
        
			$new_vdo_path   = $config['VDO_DIR']. '/' .$vdoname;
			if ( !rename($vdo_path, $new_vdo_path) ) {
				/* $errors[]   = 'Failed to move uploaded file!'; */
			}
			
			$cgi = ( strpos(php_sapi_name(), 'cgi') ) ? 'env -i ' : NULL;
			$thumb_img = '';
			if(!empty($task['thumburl'])){
				$thumb_img = $task['thumburl'];
			}
			$cmd = $cgi.$config['phppath']
		    	." ".$config['BASE_DIR']."/scripts/convert_videos.php"
		    	." ".$vdoname
		    	." ".$video_id
		    	." ".$new_vdo_path
				." ".'true'
				." ".$thumb_img
		    ."";
			$log = $config['LOG_DIR']. '/' .$video_id. '.log';
			log_conversion($log,"文件下载完成，开始转码，启动脚本:".$cmd);
			log_conversion($log,$cmd);
			shell_exec($cmd);
			$duration    = get_video_duration($new_vdo_path, $video_id);
			$sql         = "UPDATE video SET 
							duration = '" .mysql_real_escape_string($duration). "', 
							vdoname = '" .mysql_real_escape_string($vdoname). "' 
							WHERE VID = " .intval($video_id). " LIMIT 1";
			$conn->execute($sql);
			log_conversion($log,"end convert download file,task finlished");
			$sql = "UPDATE grap_video_task set status=2,msg='任务正常完成',md5 = '".$md5."' , file_size=".$file_size." WHERE id=".$task[id];
			$rs  = $conn->execute($sql);
		}else{
			//文件已经存在不需要重新下载
			$sql = "UPDATE grap_video_task set status=5,msg='文件md5值已经存在',md5 = '".$md5."' , file_size=".$file_size." WHERE id=".$task[id];
			$rs  = $conn->execute($sql);
			unlink($vdo_path);
			exit();
		}
		
	}
}

