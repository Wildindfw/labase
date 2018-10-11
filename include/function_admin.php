<?php
defined('_VALID') or die('Restricted Access!');

require 'version.php';
require_once ($config['BASE_DIR']. '/include/function_thumbs.php');

// send hears - we dont cache anything in siteadmin
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

function channelExists( $chid, $section = '' )
{
    global $conn;
    
	if ( $section == 'game' ) {
		$sql = "SELECT category_id FROM game_categories WHERE category_id = '" .mysql_real_escape_string($chid). "' LIMIT 1";		
	} elseif ( $section == 'album' ) {
		$sql = "SELECT CID FROM album_categories WHERE CID = '" .mysql_real_escape_string($chid). "' LIMIT 1";
	} elseif ( $section == 'notice' ) {
		$sql = "SELECT category_id FROM notice_categories WHERE category_id = '" .mysql_real_escape_string($chid). "' LIMIT 1";		
	} else {
		$sql = "SELECT CHID FROM channel WHERE CHID = '" .mysql_real_escape_string($chid). "' LIMIT 1";     
    }
    
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function categoryExists( $chid, $game=false )
{
    return channelExists($chid, $game);
}

function getVideoDuration( $vid )
{
    global $config;
  
    $flv = $config['FLVDO_DIR']. '/' .$vid. '.flv';
    if ( file_exists($flv) ) {
        exec($config['mplayer']. ' -vo null -ao null -frames 0 -identify "' .$flv. '"', $p);
        while ( list($k,$v) = each($p) ) {
            if ( $length = strstr($v, 'ID_LENGTH=') ) {
                break;
            }
        }
        
        $lx = explode('=', $length);
        
        return $lx['1'];
    }
    
    return false;
}

function regenVideoThumbs( $vid )
{
    global $config;
  
    $err        = NULL;
    $duration   = getVideoDuration($vid);
    if ( !$duration ) {
        $err = 'Failed to get video duration! Converted video not found!?';
    }
    
    $fc     = 0;
    $flv    = $config['FLVDO_DIR']. '/' .$vid. '.flv';
    if ( $err == '' ) {
        settype($duration, 'float');
        $timers = array(ceil($duration/2), ceil($duration/2), ceil($duration/3), ceil($duration/4));
        @mkdir($config['TMP_DIR']. '/thumbs/' .$vid);
        foreach ( $timers as $timer ) {
            if ( $config['thumbs_tool'] == 'ffmpeg' ) {
                $cmd = $config['ffmpeg']. ' -i ' .$flv. ' -f image2 -ss ' .$timer. ' -s ' .$config['img_max_width']. 'x' .$config['img_max_height']. ' -vframes 2 -y ' .$config['TMP_DIR']. '/thumbs/' .$vid. '/%08d.jpg';
            } else {
                $cmd = $config['mplayer']. ' ' .$flv. ' -ss ' .$timer. ' -nosound -vo jpeg:outdir=' .$config['TMP_DIR']. '/thumbs/' .$vid. ' -frames 2';
            }
            exec($cmd);
            $tmb    = ( $fc == 0 ) ? $vid : $fc. '_' .$vid;
            $fd     = get_thumb_dir($vid). '/' .$tmb. '.jpg';
            $ff     = $config['TMP_DIR']. '/thumbs/' .$vid. '/00000002.jpg';
            if ( !file_exists($ff) )
                $ff = $config['TMP_DIR']. '/thumbs/' .$vid. '/00000001.jpg';
            if ( !file_exists($ff) )
                $ff = $config['BASE_DIR']. '/images/default.gif';
            
            createThumb($ff, $fd, $config['img_max_width'], $config['img_max_height']);
            ++$fc;
        }
		delete_directory($config['TMP_DIR']. '/thumbs/' .$vid);
    }
    
    return $err;
}

function deleteVideo( $vid )
{
    global $config, $conn;
    
    $vid        = intval($vid);
    $sql        = "SELECT channel, server FROM video WHERE VID = " .$vid. " LIMIT 1";
    $rs         = $conn->execute($sql);
    $chid    	= $rs->fields['channel'];  
    $srv    	= $rs->fields['server'];
    if ( $srv != '' ) {
		delete_video_ftp($vid,$srv);
    }
    
    // Define All Video Formats Possible

	$files = video_files($vid, true);
	foreach ($files['dir'] as $file) {
		if ( file_exists($file) ) {
			@chmod($file, 0777);
			@unlink($file);
		}
	}

	// AVS thumbs format
	delete_directory(get_thumb_dir($vid));
		
	// Update Channel Video Totals
    $sql = "UPDATE channel SET total_videos = total_videos - 1 WHERE CHID = " .$chid;
    $conn->execute($sql);
    
    
    $tables = array('video_comments', 'favourite', 'playlist', 'video');
    foreach ( $tables as $table ) {
        $sql = "DELETE FROM " .$table. " WHERE VID = " .$vid;
        $conn->execute($sql);
    }
}

function deleteAlbum( $aid )
{
    global $config, $conn;
    
    $sql    = "SELECT PID FROM photos WHERE AID = " .$aid;
    $rs     = $conn->execute($sql);
    $photos = $rs->getrows();
    $index  = 0;
    foreach ( $photos as $photo ) {
		//delete photos + thumbs
		$file = $config['BASE_DIR'].'/media/photos/'.$photo['PID'].'.jpg';
		if ( file_exists($file) ) {
			@chmod($file, 0777);
			@unlink($file);
		}
		$file = $config['BASE_DIR'].'/media/photos/tmb/'.$photo['PID'].'.jpg';
		if ( file_exists($file) ) {
			@chmod($file, 0777);
			@unlink($file);
		}
		
        $sql    = "DELETE FROM photos WHERE PID = " .$photo['PID']. " LIMIT 1";
        $conn->execute($sql);
        $sql    = "DELETE FROM photo_comments WHERE PID = " .$photo['PID'];
        $conn->execute($sql);
        $sql    = "DELETE FROM spam WHERE type = 'photo' AND parent_id = " .$photo['PID'];
        $conn->execute($sql);
        ++$index;
    }
    
    $sql    = "DELETE FROM albums WHERE AID = " .$aid;
    $conn->execute($sql);
	
	//delete album cover
	$file = $config['BASE_DIR'].'/media/albums/'.$aid.'.jpg';
	if ( file_exists($file) ) {
		@chmod($file, 0777);
		@unlink($file);
	}    
}

function albumExists( $aid )
{
    global $conn;
    
    $sql    = "SELECT AID FROM albums WHERE AID = " .intval($aid). " LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();    
}

function photoExists( $pid )
{
    global $conn;
    
    $sql    = "SELECT PID FROM photos WHERE PID = " .intval($pid). " LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function videoExists( $vid )
{
    global $conn;
    
    $sql = "SELECT VID FROM video WHERE VID = '" .mysql_real_escape_string($vid). "' LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function blogExists( $bid )
{
    global $conn;
    
    $sql    = "SELECT BID FROM blog WHERE BID = " .intval($bid). " LIMIT 1";
    $conn->execute($sql);
    
    return $conn->affected_rows();
}

function insert_user_byip($options)
{
    global $conn;
    if ($options['ip'] != '') {
		$sql = "SELECT UID, username FROM signup WHERE user_ip = '" .mysql_real_escape_string($options['ip']). "' LIMIT 1";
		$rs  = $conn->execute($sql);
		if ( $conn->Affected_Rows() == 1 ) {
			$user = $rs->getrows();
			$user = $user[0];
			return $user;
		}
	}
	$user['username'] = 'NO USER WITH THIS IP';
	$user['UID'] = false;
    return $user;
}

function insert_video_title($option)
{
    global $conn;
    
    $sql = "SELECT title, thumb FROM video WHERE VID = '" .mysql_real_escape_string($option['vid']). "' LIMIT 1";
    $rs  = $conn->execute($sql);
    if ( $conn->Affected_Rows() == 1 ) {
        return $rs->getrows();
    }
    
    return 'NO VIDEO ATTACHED!';
}

function insert_video_count( $options )
{
    global $conn, $config;
    
    $active     = ( $config['approve'] == '1' ) ? " AND active = '1'" : NULL;
    $type       = ( isset($options['type']) && ( $options['type'] == 'private' or $options['type'] == 'public' ) ) ? $options['type'] : NULL;
    $sql_add    = ( isset($type) ) ? " AND type = '" .mysql_real_escape_string($type). "'" : NULL;
    $uid        = intval($options['UID']);    
    $sql        = "SELECT COUNT(VID) AS total_videos FROM video WHERE UID = " .$uid . $sql_add . $active;
    $rs         = $conn->execute($sql);
    
    return $rs->fields['total_videos'];
}

function insert_comment_count( $options )
{
    global $conn, $config;
    
    $uid        = intval($options['UID']);    
    $sql        = "SELECT COUNT(wall_id) AS total_comments FROM wall WHERE OID = " .$uid;
    $rs         = $conn->execute($sql);
    
    return $rs->fields['total_comments'];
}

function userExistsByUsername( $username )
{
    global $conn;
    
    $sql = "SELECT UID FROM signup WHERE username = '" .mysql_real_escape_string($username). "' LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function userExistsByID( $id )
{
    global $conn;
    
    $sql = "SELECT UID FROM signup WHERE UID = '" .mysql_real_escape_string($id). "' LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function makeTimeStamp($year='', $month='', $day='')
{
    if(empty($year)) {
        $year = strftime('%Y');
    }
    
    if(empty($month)) {
        $month = strftime('%m');
    }
    
    if(empty($day)) {
        $day = strftime('%d');
    }
                                       
    return mktime(0, 0, 0, $month, $day, $year);
}

function insert_get_video_title( $options )
{
    global $conn;
    
    $sql    = "SELECT title FROM video WHERE VID = '" .mysql_real_escape_string($options['VID']). "' LIMIT 1";
    $rs     = $conn->execute($sql);
    if ( $conn->Affected_Rows() == 1 ) {
        return $rs->fields['title'];
    }
    
    return false;
}

function insert_get_game_title( $options )
{
    global $conn;
    
    $sql    = "SELECT title FROM game WHERE GID = '" .mysql_real_escape_string($options['GID']). "' LIMIT 1";
    $rs     = $conn->execute($sql);
    if ( $conn->Affected_Rows() == 1 ) {
        return $rs->fields['title'];
    }
    
    return false;
}

function insert_video_thumbs( $options )
{
    global $config;
    
    $vid    = intval($options['VID']);
    $vkey   = $options['vkey'];
    $thumb  = $options['thumb'];
    $output = array();
    for ( $i=1; $i<=20; $i++ ) {
        $tmb            = get_thumb_dir($vid). '/' .$i. '.jpg';
        if ( file_exists($tmb) && is_file($tmb) ) {
            $class      = ( $thumb == $i ) ? 'tmb-active img-responsive' : 'tmb img-responsive';
			$output[]   = '<div class="col-sm-4 m-b-10">';
            $output[]   = '<img src="' .get_thumb_url($vid). '/' .$i. '.jpg" id="change_tmb_' .$vkey. '_' .$i. '" class="' .$class. '">';
			$output[]   = '</div>';
        }
    }
    
    return implode("\n", $output);
}

function insert_vvideo_thumbs( $options )
{
    global $config;
    
    $vid    = intval($options['VID']);
    $output = array();
    for ( $i=1; $i<=20; $i++ ) {
        $tmb            = get_thumb_dir($vid). '/' .$i. '.jpg';
        if ( file_exists($tmb) && is_file($tmb) ) {
            $class      = 'img-responsive';
			$output[]   = '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 m-b-10">';
            $output[]   = '<img src="' .get_thumb_url($vid). '/' .$i. '.jpg" id="change_tmb_' .$vkey. '_' .$i. '" class="' .$class. '">';
			$output[]   = '</div>';
        }
    }
    
    return implode("\n", $output);
}

function insert_channel_count( $options )
{
    global $conn, $config;
    
    $active     = ( $config['approve'] == '1' ) ? " AND active = '1'" : NULL;
    $sql        = "SELECT COUNT(VID) AS total_videos FROM video WHERE channel = '" .intval($options['CHID']). "'" .$active;
    $rs         = $conn->execute($sql);
    
    return $rs->fields['total_videos'];
}

function insert_uid_to_name( $options )
{
    global $conn;
    
    $uid    = intval($options['uid']);
    $sql    = "SELECT username FROM signup WHERE UID = " .$uid. " LIMIT 1";
    $rs     = $conn->execute($sql);
    if ( $conn->Affected_Rows() == 1 ) {
        return $rs->fields['username'];
    }
    
    return 'unknown';
}

function update_config( $config )
{
    $buffer         = array();
    $buffer[]       = '<?php';
    $buffer[]       = 'defined(\'_VALID\') or die(\'Restricted Access!\');';
    foreach( $config as $key => $value ) {
        if ( !preg_match('/^[A-Z_]+/', $key) && !preg_match('/^db_(.*)/', $key) ) {
        	$buffer[]   = '$config[\'' .$key. '\'] = \'' .str_replace('\'', '&#039;', $value). '\';';
        }
    }
    $buffer[]       = '?>';
    
    $data           = implode("\n", $buffer);
    $path           = $config['BASE_DIR']. '/include/config.local.php';

    $fp = fopen($path, 'wb');
    if ($fp) {
        flock($fp, LOCK_EX);
        $len = strlen($data);
        fwrite($fp, $data, $len);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

function update_smarty()
{
    global $config, $smarty;
    
    foreach ( $config as $key =>  $value ) {
	$smarty->assign($key, $value);
    }
}

function deleteUser( $uid )
{
    global $config, $conn;

    $sql    = "SELECT UID FROM signup WHERE username = 'anonymous' LIMIT 1";
    $rs     = $conn->execute($sql);
    $auid   = intval($rs->fields['UID']);
	
	//REMOVE TOTAL COMMENTS
	//update total comments - blogs
	$sql = "SELECT BID FROM blog_comments WHERE UID = " .$uid;
	$rs = $conn->execute($sql);
	foreach ($rs as $value) {
		$sql        = "UPDATE blog SET total_comments = total_comments-1 WHERE BID = " .$value['BID'];
		$conn->execute($sql);
	}
	
	//update total comments - games
	$sql = "SELECT GID FROM game_comments WHERE UID = " .$uid;
	$rs = $conn->execute($sql);
	foreach ($rs as $value) {
		$sql        = "UPDATE game SET total_comments = total_comments-1 WHERE GID = " .$value['GID'];
		$conn->execute($sql);
	}

	//update total comments - photos / albums
	$sql = "SELECT PID FROM photo_comments WHERE UID = " .$uid;
	$rs = $conn->execute($sql);
	foreach ($rs as $value) {
		$sql        = "UPDATE photos SET total_comments = total_comments-1 WHERE PID = " .$value['PID'];
		$conn->execute($sql);
		$sql = "SELECT AID FROM photos WHERE PID = " .$value['PID']. " LIMIT 1";
		$rsa = $conn->execute($sql);
		$a_id   = intval($rsa->fields['AID']);
		$sql        = "UPDATE albums SET total_comments = total_comments-1 WHERE AID = " .$a_id;
		$conn->execute($sql);			
	}

	//update total comments - videos
	$sql = "SELECT VID FROM video_comments WHERE UID = " .$uid;
	$rs = $conn->execute($sql);
	foreach ($rs as $value) {
		$sql        = "UPDATE video SET total_comments = total_comments-1 WHERE VID = " .$value['VID'];
		$conn->execute($sql);
	}		
	//END REMOVE TOTAL COMMENTS	
	
    $sql    = "UPDATE video SET UID = " .$auid. " WHERE UID = " .$uid;
    $conn->execute($sql);
    $sql    = "UPDATE albums SET UID = " .$auid. " WHERE UID = " .$uid;
    $conn->execute($sql);
    $sql    = "UPDATE game SET UID = " .$auid. " WHERE UID = " .$uid;
    $conn->execute($sql);
	
    $tables = array('blog', 'signup', 'users_prefs', 'blog_comments', 'game_comments', 'notice_comments', 'photo_comments', 'video_comments', 
					'wall', 'confirm', 'favourite', 'friends', 'game_favorites', 'game_flags', 'game_rating_id', 'notice', 'photo_favorites', 
					'photo_flags', 'photo_rating_id', 'playlist', 'spam', 'users_blocks', 'users_flags', 'users_online', 'users_rating_id', 
					'video_comments', 'video_flags', 'video_subscribe', 'video_vote_users');
    foreach ( $tables as $table ) {
        $sql = "DELETE FROM " .$table. " WHERE UID = '" .mysql_real_escape_string($uid). "'";
        $conn->execute($sql);
    }
	
	//delete avatar
	$file = $config['BASE_DIR'].'/media/users/'.$uid.'.jpg';
	if ( file_exists($file) ) {
		@chmod($file, 0777);
		@unlink($file);
	}	

	$file = $config['BASE_DIR'].'/media/users/orig/'.$uid.'.jpg';
	if ( file_exists($file) ) {
		@chmod($file, 0777);
		@unlink($file);
	}	
	
}

function deleteBlog( $bid )
{
    global $conn;
    
    $bid    = intval($bid);
    $sql    = "SELECT UID FROM blog WHERE BID = " .$bid. " LIMIT 1";
    $rs     = $conn->execute($sql);
    $buid   = $rs->fields['UID'];
    $sql    = "UPDATE signup SET total_blogs = total_blogs-1 WHERE UID = " .$buid. " LIMIT 1";
    $conn->execute($sql);
    $sql    = "DELETE FROM blog_comments WHERE BID = " .$bid;
    $conn->execute($sql);
    $sql    = "DELETE FROM blog WHERE BID = " .$bid. " LIMIT 1";
    $conn->execute($sql);
}

function deleteNotice( $nid )
{
    global $conn;
    
    $nid    = intval($nid);
    $sql    = "SELECT category FROM notice WHERE NID = " .$nid. " LIMIT 1";
    $rs     = $conn->execute($sql);
	if ( $conn->Affected_Rows() ) {
		$cid   = $rs->fields['category'];
		$sql    = "UPDATE notice_categories SET total_notices = total_notices-1 WHERE category_id = " .$cid. " LIMIT 1";
		$conn->execute($sql);		
	}
    $sql    = "DELETE FROM notice_comments WHERE NID = " .$nid;
    $conn->execute($sql);
    $sql    = "DELETE FROM notice WHERE NID = " .$nid. " LIMIT 1";
    $conn->execute($sql);
}

function gameExists( $gid )
{
    global $conn;
    
    $gid    = intval($gid);
    $sql    = "SELECT GID FROM game WHERE GID = " .$gid. " LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function deleteGame( $gid )
{
    global $config, $conn;
    
    $gid    = intval($gid);
    $sql    = "SELECT UID, category FROM game WHERE GID = " .$gid. " LIMIT 1";
    $rs     = $conn->execute($sql);
    $guid   = intval($rs->fields['UID']);
	$gcid   = $rs->fields['category'];
    $sql    = "UPDATE signup SET total_games = total_games - 1 WHERE UID = " .$guid. " LIMIT 1";
    $conn->execute($sql);
    $sql = "UPDATE game_categories SET total_games = total_games - 1 WHERE category_id = " .$gcid;
    $conn->execute($sql);
    $tables = array('game_comments', 'game_favorites', 'game', 'game_rating_ip', 'game_rating_id',);
    foreach ( $tables as $table ) {
        $sql = "DELETE FROM " .$table. " WHERE GID = " .$gid;
        $conn->execute($sql);
    }

	$swf = $config['BASE_DIR']. '/media/games/swf/' .$gid. '.swf';
	if ( file_exists($swf) ) {
        @chmod($swf, 0777);
        @unlink($swf);
    }
	
	$thumb = $config['BASE_DIR']. '/media/games/tmb/' .$gid. '.jpg';
	if ( file_exists($thumb) ) {
        @chmod($thumb, 0777);
        @unlink($thumb);
    }
	
	$thumb = $config['BASE_DIR']. '/media/games/tmb/orig/' .$gid. '.jpg';
	if ( file_exists($thumb) ) {
        @chmod($thumb, 0777);
        @unlink($thumb);
    }	
}

function upload_video_ftp( $video_id )
{
    global $config;
    
    $conn	= ftp_connect($config['ftp_host']);
    debug_ftp('ftp_connect->' .$config['ftp_host']);
    $ftp_login	= ftp_login($conn, $config['ftp_username'], $config['ftp_password']);
    debug_ftp('ftp_login->' .$config['ftp_username']. ' - ' .$config['ftp_password']);
    if ( !$conn or !$ftp_login ) {
	die('Failed to connect to FTP server!');
    }
    
    $src = $config['BASE_DIR']. '/media/videos/flv/' .$video_id. '.flv';
    $dst = $config['ftp_root']. '/media/videos/flv/' .$video_id. '.flv';
    if ( file_exists($src) ) {
	ftp_pasv($conn, 1);
	debug_ftp('ftp_pasv->1');
	ftp_delete($conn, $dst);
	debug_ftp('ftp_delete->' .$dst);
	ftp_put($conn, $dst, $src, FTP_BINARY);
	debug_ftp('ftp_put->' .$src. ' - ' .$dst. ' (FTP_BINARY)');
	ftp_site($conn, sprintf('CHMOD %u %s', 777, $dst));
	debug_ftp('ftp_site->' .sprintf('CHMOD %u %s', 777, $dst));
    }
}

function get_vid_server($srv)
{
    global $conn;
	$sql = "SELECT * FROM servers WHERE video_url = '".$srv."'";
	$rs  = $conn->execute($sql);
	if ($conn->Affected_Rows()) {
		$servers = $rs->getrows();
		return $servers[0];
	} else {
		die('Failed to find a active server! Please check your settings!');
	}
}

function delete_video_ftp( $video_id, $srv )
{
    global $config, $conn;
    
    $server 	= get_vid_server($srv);
	$conn_id    = ftp_connect($server['server_ip']);
	$ftp_root 	= $server['ftp_root'];
	$ftp_login  = ftp_login($conn_id, $server['ftp_username'], $server['ftp_password']);
	if ( !$conn_id or !$ftp_login ) {
        die('Failed to connect to FTP server!');
    }

	
	
	ftp_pasv($conn_id, 1);

	if ( !ftp_chdir($conn_id, $ftp_root) ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}
	
	// Change dir to flv and delete flv
	if ( !ftp_chdir($conn_id, 'flv') ) {
	    die('Failed to change directory to: flv');
	}	
	ftp_delete($conn_id, $video_id.'.flv');
	if ( !ftp_chdir($conn_id, '..') ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}		

	// Change dir to iphone and delete video
	if ( !ftp_chdir($conn_id, 'iphone') ) {
	    die('Failed to change directory to: iphone');
	}	
	ftp_delete($conn_id, $video_id.'.mp4');
	if ( !ftp_chdir($conn_id, '..') ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}		

	// Change dir to hd and delete video
	if ( !ftp_chdir($conn_id, 'hd') ) {
	    die('Failed to change directory to: hd');
	}	
	ftp_delete($conn_id, $video_id.'.mp4');
	if ( !ftp_chdir($conn_id, '..') ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}	

	// Change dir to h264 and delete video
	if ( !ftp_chdir($conn_id, 'h264') ) {
	    die('Failed to change directory to: iphone');
	}
	$files = video_files($video_id);
	foreach ($files['server_h264_fn'] as $file) {
		ftp_delete($conn_id, $file);
	}
	if ( !ftp_chdir($conn_id, '..') ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}	
	
	ftp_close($conn_id);    

}

function debug_ftp( $msg )
{
    $DEBUG_FTP = false;
    if ( $DEBUG_FTP ) {
	echo $msg, "\n";
    }
}

function get_player_skins()
{               
    global $config;
            
    $skins      = array();
    $skins_dir  = $config['BASE_DIR']. '/media/player/skins';
	clearstatcache();
    if ( file_exists($skins_dir) && is_dir($skins_dir) ) {
        $files  = scandir($skins_dir);
        foreach ( $files as $file ) {
            if ( $file != 'index.php' && $file != '.' && $file != '..' && $file != 'index.html') {
                if ( is_dir($skins_dir. '/' .$file) ) {
                    $skins[] = $file;
                }
            }
        }
    }
        
    return $skins;
}

function send_game_approve_email($game_id)
{
	global $config, $conn;
	
	$sql = "SELECT g.GID, g.title, s.username, s.email FROM game AS g, signup AS s
	        WHERE g.GID = ".intval($game_id)." AND g.UID = s.UID
			LIMIT 1";
	$rs  = $conn->execute($sql);
	
	$gid      = $rs->fields['GID'];
	$title    = $rs->fields['title'];
	$username = $rs->fields['username'];
	$email    = $rs->fields['email'];
	
	$game_url  = $config['BASE_URL']. '/game/' .$gid. '/' .prepare_string($title);
	$game_link = '<a href="'.$game_url.'">'.$game_url.'</a>';
	$search     = array('{$site_title}', '{$site_name}', '{$username}', '{$game_link}', '{$baseurl}');
    $replace    = array($config['site_title'], $config['site_name'], $username, $game_link, $config['BASE_URL']);
    
	if (!class_exists('VMail')) {
		require $config['BASE_DIR']. '/classes/email.class.php';
	}
	
	$mail = new VMail();
	$mail->sendPredefined($email, 'game_approve', $search, $replace);
}

function send_video_approve_email($video_id)
{
    global $config, $conn;
    
    $sql        = "SELECT v.VID, v.title, s.username, s.email FROM video AS v, signup AS s
                  WHERE v.VID = " .intval($video_id). " AND v.UID = s.UID
                  LIMIT 1";
    $rs         = $conn->execute($sql);
	
	$vid		= intval($rs->fields['VID']);
	$title		= $rs->fields['title'];
	$username	= $rs->fields['username'];
	$email		= $rs->fields['email'];
	
	$video_url  = $config['BASE_URL']. '/video/'. $vid. '/' .prepare_string($title);
	$video_link = '<a href="'.$video_url.'">'.$video_url.'</a>';
    $search     = array('{$site_title}', '{$site_name}', '{$username}', '{$video_link}', '{$baseurl}');
    $replace    = array($config['site_title'], $config['site_name'], $username, $video_link, $config['BASE_URL']);

	if (!class_exists('VMail')) {
		require $config['BASE_DIR']. '/classes/email.class.php';
	}
	
	$mail = new VMail();
    $mail->sendPredefined($email, 'video_approve', $search, $replace);
}

function send_album_approve_email($album_id)
{
    global $config, $conn;
    
	$sql        = "SELECT a.AID, a.name, s.username, s.email FROM albums AS a, signup AS s
	               WHERE a.AID = ".intval($album_id)." AND a.UID = s.UID
				   LIMIT 1";
	$rs         = $conn->execute($sql);
	
	$aid		= intval($rs->fields['AID']);
	$name		= $rs->fields['name'];
	$username	= $rs->fields['username'];
	$email		= $rs->fields['email'];
	$album_url	= $config['BASE_URL']. '/album/' .$aid. '/' .prepare_string($name);
	$album_link	= '<a href="'.$album_url.'">'.$album_url.'</a>';
    $search     = array('{$site_title}', '{$site_name}', '{$username}', '{$album_link}', '{$baseurl}');
    $replace    = array($config['site_title'], $config['site_name'], $username, $album_link, $config['BASE_URL']);

	if (!class_exists('VMail')) {
		require $config['BASE_DIR']. '/classes/email.class.php';
	}
	
	$mail = new VMail();
    $mail->sendPredefined($email, 'video_approve', $search, $replace);
}

function insert_tmb_path($options)
{
	global $config;
	
	$vid   = $options['vid'];
	$index = intval( ($vid - 1) / $config['max_thumb_folders'] );
	$tmb_folder = 'tmb';
	if ($index !== 0) {
		$tmb_folder = 'tmb'.$index;
	}

	$output = $config['BASE_URL'].'/media/videos/'.$tmb_folder.'/'.$vid;

	return $output;
}

function toAscii($str, $replace=array(), $delimiter='-') {
	setlocale(LC_ALL, 'en_US.UTF8');	
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}
	if (function_exists('iconv')) {
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	} else {
		$clean = $str;
	}	
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

function channelSlugExists( $slug, $cid, $section = '' )
{
    global $conn;
	
	if ( $section == 'game' ) {
        $sql = "SELECT slug FROM game_categories WHERE slug = '" .mysql_real_escape_string($slug). "' AND category_id != '" .mysql_real_escape_string($cid). "' LIMIT 1";
    } elseif ( $section == 'album' ) {
		$sql = "SELECT slug FROM album_categories WHERE slug = '" .mysql_real_escape_string($slug). "' AND CID != '" .mysql_real_escape_string($cid). "' LIMIT 1";
	} else {
        $sql = "SELECT slug FROM channel WHERE slug = '" .mysql_real_escape_string($slug). "' AND CHID != '" .mysql_real_escape_string($cid). "' LIMIT 1";
    } 	
    
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function channelNameExists( $name, $cid, $section = '' )
{
    global $conn;
	
	if ( $section == 'game' ) {
        $sql = "SELECT category_name FROM game_categories WHERE category_name = '" .mysql_real_escape_string($name). "' AND category_id != '" .mysql_real_escape_string($cid). "' LIMIT 1";
	} elseif ( $section == 'notice' ) {
        $sql = "SELECT name FROM notice_categories WHERE name = '" .mysql_real_escape_string($name). "' AND category_id != '" .mysql_real_escape_string($cid). "' LIMIT 1";		
    } elseif ( $section == 'album' ) {
		$sql = "SELECT name FROM album_categories WHERE name = '" .mysql_real_escape_string($name). "' AND CID != '" .mysql_real_escape_string($cid). "' LIMIT 1";
	} else {
        $sql = "SELECT name FROM channel WHERE name = '" .mysql_real_escape_string($name). "' AND CHID != '" .mysql_real_escape_string($cid). "' LIMIT 1";
    } 	
    
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

function channelCountItems( $cid, $section = '' )
{
    global $conn;
	
	if ( $section == 'game' ) {
        $sql = "SELECT * FROM game WHERE category = " .intval($cid). " AND status = 1";
	} elseif ( $section == 'notice' ) {
        $sql = "SELECT * FROM notice WHERE category = " .intval($cid);
    } elseif ( $section == 'album' ) {
		$sql = "SELECT * FROM albums WHERE category = " .intval($cid). " AND status = 1";
	} else {
		$sql = "SELECT * FROM video WHERE active = '1' AND channel = " .intval($cid). " AND active = 1";
    } 	
    
    $rs = $conn->execute($sql);
    
    return $rs->NumRows();
}



function deletePhoto( $pid ) {
	
    global $config, $conn;
    
	$aid = null;
	
	$sql = "SELECT AID FROM photos WHERE PID = '" .$pid. "' LIMIT 1";
	$rs  = $conn->execute($sql);
	$aid = intval($rs->fields['AID']);	

	$sql = "UPDATE albums SET total_photos = total_photos - 1 WHERE AID = '" .mysql_real_escape_string($aid). "' LIMIT 1";
	$conn->execute($sql);
	
	//delete photo + thumb
	$file = $config['BASE_DIR'].'/media/photos/'.$pid.'.jpg';
	if ( file_exists($file) ) {
		@chmod($file, 0777);
		@unlink($file);
	}
	$file = $config['BASE_DIR'].'/media/photos/tmb/'.$pid.'.jpg';
	if ( file_exists($file) ) {
		@chmod($file, 0777);
		@unlink($file);
	}
	
	$sql    = "DELETE FROM photos WHERE PID = " .$pid. " LIMIT 1";
	$conn->execute($sql);
	$sql    = "DELETE FROM photo_comments WHERE PID = " .$pid;
	$conn->execute($sql);
	$sql    = "DELETE FROM spam WHERE type = 'photo' AND parent_id = " .$pid;
	$conn->execute($sql);
	
	return $aid;

}

function delete_video_comment($cid) {
	
	global $conn;
	
		$sql = "SELECT VID FROM video_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$rs  = $conn->execute($sql);

        if ( $conn->Affected_Rows() ) {
			$vid = intval($rs->fields['VID']);
			$sql = "DELETE FROM video_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
			$conn->execute($sql);

			$sql = "UPDATE video SET com_num = com_num - 1 WHERE VID = '" .mysql_real_escape_string($vid). "' LIMIT 1";
			$conn->execute($sql);			
			return true;		
        } else {
			return false;
		}	
}

function delete_photo_comment($cid) {
	
	global $conn;
	
		$sql = "SELECT PID FROM photo_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$rs  = $conn->execute($sql);

        if ( $conn->Affected_Rows() ) {
			$pid = intval($rs->fields['PID']);
			$sql = "DELETE FROM photo_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
			$conn->execute($sql);
			
			$sql = "UPDATE photos SET total_comments = total_comments - 1 WHERE PID = '" .mysql_real_escape_string($pid). "' LIMIT 1";
			$conn->execute($sql);

			$sql = "SELECT AID FROM photos WHERE PID = '" .mysql_real_escape_string($pid). "' LIMIT 1";
			$rs  = $conn->execute($sql);
			$aid = intval($rs->fields['AID']);

			$sql = "UPDATE albums SET total_comments = total_comments - 1 WHERE AID = '" .mysql_real_escape_string($aid). "' LIMIT 1";
			$conn->execute($sql);
			
			return $aid;
        } else {
			return false;
		}	
}

function delete_game_comment($cid) {
	
	global $conn;
	
		$sql = "SELECT GID FROM game_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$rs  = $conn->execute($sql);

        if ( $conn->Affected_Rows() ) {
			$gid = intval($rs->fields['GID']);
			$sql = "DELETE FROM game_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
			$conn->execute($sql);

			$sql = "UPDATE game SET total_comments = total_comments - 1 WHERE GID = '" .mysql_real_escape_string($gid). "' LIMIT 1";
			$conn->execute($sql);			
			return true;		
        } else {
			return false;
		}	
}

function delete_blog_comment($cid) {
	
	global $conn;
	
		$sql = "SELECT BID FROM blog_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$rs  = $conn->execute($sql);

        if ( $conn->Affected_Rows() ) {
			$bid = intval($rs->fields['BID']);
			$sql = "DELETE FROM blog_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
			$conn->execute($sql);

			$sql = "UPDATE blog SET total_comments = total_comments - 1 WHERE BID = '" .mysql_real_escape_string($bid). "' LIMIT 1";
			$conn->execute($sql);			
			return true;		
        } else {
			return false;
		}	
}

function delete_notice_comment($cid) {
	
	global $conn;
	
		$sql = "SELECT NID FROM notice_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$rs  = $conn->execute($sql);

        if ( $conn->Affected_Rows() ) {
			$nid = intval($rs->fields['NID']);
			$sql = "DELETE FROM notice_comments WHERE CID = '" .mysql_real_escape_string($cid). "' LIMIT 1";
			$conn->execute($sql);

			$sql = "UPDATE notice SET total_comments = total_comments - 1 WHERE NID = '" .mysql_real_escape_string($nid). "' LIMIT 1";
			$conn->execute($sql);			
			return true;		
        } else {
			return false;
		}	
}

function delete_user_comment($cid) {
	
	global $conn;
	
		$sql = "DELETE FROM wall WHERE wall_id = '" .mysql_real_escape_string($cid). "' LIMIT 1";
		$conn->execute($sql);
        if ( $conn->Affected_Rows() ) {	
			return true;		
        } else {
			return false;
		}	
}

function blog_output_admin($content) 
{
	global $config;
	$search     = array('/\[b\](.*?)\[\/b\]/ms', '/\[i\](.*?)\[\/i\]/ms', '/\[u\](.*?)\[\/u\]/ms',
						'/\[img\](.*?)\[\/img\]/ms', '/\[email\](.*?)\[\/email\]/ms', '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
						'/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms', '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms', '/\[quote](.*?)\[\/quote\]/ms',
						'/\[list\=(.*?)\](.*?)\[\/list\]/ms', '/\[list\](.*?)\[\/list\]/ms', '/\[\*\]\s?(.*?)\n/ms');
	$replace    = array('<strong>\1</strong>', '<em>\1</em>', '<u>\1</u>', '<img src="\1" alt="\1" />',
						'<a href="mailto:\1">\1</a>', '<a href="\1">\2</a>', '<span style="font-size:\1%">\2</span>',
						'<span style="color:\1">\2</span>', '<blockquote>\1</blockquote>', '<ol start="\1">\2</ol>',
						'<ul>\1</ul>', '<li>\1</li>');
	$content    = preg_replace($search, $replace, $content);
	$content    = preg_replace('/\[photo=(.*?)\]/ms', '<div class="row"><div class="col-xs-8 col-xs-offset-2"><center><img src="' .$config['BASE_URL']. '/media/photos/\1.jpg" alt="" class="blog_image" /></center></div></div>', $content);
	$content    = preg_replace('/\[video=(.*?)\]/ms', '<div class="row"><div class="col-xs-8 col-xs-offset-2"><div class="blog_video"><div id="blog_video_\1"><iframe src="' .$config['BASE_URL'].'/view.php?VID=\1" frameborder="0" allowfullscreen></iframe></div></div></div></div>', $content);
	$content    = str_replace("\r", "", $content);
	$content    = "<p>".ereg_replace("(\n){2,}", "</p><p>", $content)."</p>";
	
	return $content;
}

?>
