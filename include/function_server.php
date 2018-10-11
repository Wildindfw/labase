<?php
defined('_VALID') or die('Restricted Access!');
//mod random multiserver ftp start
function listDir($dir){
	$handler = opendir($dir);
    while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
        if ($filename != "." && $filename != "..") {
                $files[] = $filename ;
           }
      }
	return $files;
}
function log_conversion2($file_path, $text)
{   
    $file_dir = dirname($file_path);
    if( !file_exists($file_dir) or !is_dir($file_dir) or !is_writable($file_dir) ) {
        return false;
    }
                    
    $write_mode = 'w';
    if( file_exists($file_path) && is_file($file_path) && is_writable($file_path) ) {
        $write_mode = 'a';
    }
                                
    if( !$handle = fopen($file_path, $write_mode) ) {
        return false;
    }
                                                
    if( fwrite($handle, date("Y-m-d H:i:s").':'.$text. "\n") == false ) {
        return false;
    }
                                                            
    @fclose($handle);
}  
function upload_m3u8_video($m3u8_path, $ip, $username, $password, $ftp_root,$vid)
{
	global $config;
	$log =  "enter the function !\n\n";
	log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
	$conn_id    = ftp_connect($ip);
	$ftp_login  = ftp_login($conn_id, $username, $password);
	if ( !$conn_id or !$ftp_login ) {
		$log =  "Failed to connect to FTP server!\n\n";
		log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
        die('Failed to connect to FTP server!');
    }
	ftp_pasv($conn_id, 1);
	if ( !ftp_chdir($conn_id, $ftp_root) ) {
   	$log =  "Failed to change directory to".$ftp_root."!\n\n";
		log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
	    die('Failed to change directory to: ' .$ftp_root);
	}
	if (file_exists($m3u8_path)) {
		if ( !ftp_chdir($conn_id, 'm3u8') ) {
				$log =  "Failed to change directory to: m3u8!\n\n";
			    log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
				die('Failed to change directory to: m3u8');
		}
		$date = date('Ymd');
		if ( !ftp_chdir($conn_id, $date) ) {
			ftp_mkdir($conn_id,$date);
			ftp_chdir($conn_id, $date);
		}
		
		
		$paths = explode(DIRECTORY_SEPARATOR,$m3u8_path);
		$index = count($paths)-2;
		$dirname = $paths[$index];
		
		ftp_mkdir($conn_id,$dirname);
		if ( !ftp_chdir($conn_id, $dirname) ) {
			$log =  "Failed to change directory to".$dirname."!\n\n";
		    log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
		    die('Failed to change directory to:'.$dirname);
		}
		$files = listDir($m3u8_path);
		foreach($files as $file){
			$file_path = $m3u8_path.$file;
			ftp_delete($conn_id, $file);
			echo "send file ".$file_path.'to'.$file;
			ftp_put($conn_id, $file, $file_path , FTP_BINARY);
			ftp_site($conn_id, sprintf('CHMOD %u %s', 777, $filename));
			unlink($file_path);
		}
		if ( !ftp_chdir($conn_id, '..') ) {
			$log =  "2Failed to change directory to".$ftp_root."!\n\n";
		    log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
		    die('Failed to change directory to: ' .$ftp_root);
	    }	
	}else{
	   $log =  "no m3u8 here\n\n";
	   log_conversion2($config['LOG_DIR']. '/' .$vid. '.log', $log);
	   echo 'no hd:'.$hd;
	   return '';
	}
	ftp_close($conn_id);
	return $date.'/'.$dirname;
	
}



function upload_pics($pic_path, $ip, $username, $password, $ftp_root)
{
	$conn_id    = ftp_connect($ip);
	$ftp_login  = ftp_login($conn_id, $username, $password);
	if ( !$conn_id or !$ftp_login ) {
        die('Failed to connect to FTP server!');
    }
	ftp_pasv($conn_id, 1);
	if ( !ftp_chdir($conn_id, $ftp_root) ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}		
	
	if (file_exists($pic_path)) {
		if ( !ftp_chdir($conn_id, 'tmb') ) {
		    die('Failed to change directory to: tmb');
		}
		$paths = explode(DIRECTORY_SEPARATOR,$pic_path);
		$index = count($paths)-2;
		
		$dirname = $paths[$index];
		//var_dump($paths);die();
		ftp_mkdir($conn_id,$dirname);
		if ( !ftp_chdir($conn_id, $dirname) ) {
		    die('Failed to change directory to:'.$dirname);
		}
		$files = listDir($pic_path);
		foreach($files as $file){
			$file_path = $pic_path.$file;
			ftp_delete($conn_id, $file);
			echo "send file ".$file_path.'to'.$file;
			ftp_put($conn_id, $file, $file_path , FTP_BINARY);
			ftp_site($conn_id, sprintf('CHMOD %u %s', 777, $filename));
			unlink($file_path);
		}
		if ( !ftp_chdir($conn_id, '..') ) {
		    die('Failed to change directory to: ' .$ftp_root);
	    }	
	}else{
	   echo 'no pics:'.$hd;
	}
	ftp_close($conn_id);
}

function get_server()
{
	global $conn;
	$sql = "SELECT * FROM servers WHERE status = '1'";
	$sql2 = "SELECT COUNT(server_id) AS total_server FROM servers WHERE status = '1' ORDER BY last_used ASC";
	$rs  = $conn->execute($sql);
	if ($conn->Affected_Rows()) {
		$servers = $rs->getrows();
		
		$total_serv = $rs->RecordCount();
		$counter = $total_serv-1;
		
		for ( $i = 0; $i < $total_serv; $i++)
		{
		    //echo "This is server : $i <br />";
		    //echo "***********************************************************<br />";
			//echo $servers[$i]['url'];
			//echo "<br /><br />";
			if ($servers[$i]['current_used'] == 1)
			{
              update_server_to_off($servers[$i]['server_id']);//set current server used to 0.
			  if ($i < $counter)
			  {
			    $params = $i+1;
			  }
			  else
			  {
			    $params = 0;
			  }
			 // echo "Param is: ".$params;
			  update_server_to_on($servers[$params]['server_id']);//set next server used to 1.
			  return $servers[$i];
			  //echo "<strong style='color:#FF0000;'>######## This Server Was Currently in Used. ########</strong> </br>";
			  

			}
			
			//echo "<br />***********************************************************<br />";
		
		}

	} else {
		die('Failed to find a active server! Please check your settings!');
	}	
}
function update_server_to_off($server_id)
{
	global $conn;
	$conn->execute("UPDATE servers SET current_used = '0' WHERE server_id = ".$server_id." LIMIT 1");
}
function update_server_to_on($server_id)
{
	global $conn;
	$conn->execute("UPDATE servers SET current_used = '1' WHERE server_id = ".$server_id." LIMIT 1");
}
//mod random multiserver ftp end
function update_server_used($server)
{
	global $conn;
	$conn->execute("UPDATE servers SET current_used = '1' WHERE server_id = ".$server['server_id']." LIMIT 1");
}

function update_server($server)
{
	global $conn;
	$conn->execute("UPDATE servers SET last_used = '".date('Y-m-d h:i:is')."', current_used = '0'
	                WHERE server_id = ".$server['server_id']." LIMIT 1");
}

function upload_video($flv, $iphone, $hd, $ip, $username, $password, $ftp_root)
{
	$conn_id    = ftp_connect($ip);
	$ftp_login  = ftp_login($conn_id, $username, $password);
	if ( !$conn_id or !$ftp_login ) {
        die('Failed to connect to FTP server!');
    }
	ftp_pasv($conn_id, 1);
	if ( !ftp_chdir($conn_id, $ftp_root) ) {
	    die('Failed to change directory to: ' .$ftp_root);
	}		
	if (file_exists($flv)) {
		if ( !ftp_chdir($conn_id, 'flv') ) {
		    die('Failed to change directory to: flv');
		}	
		$filename = basename($flv);
		ftp_delete($conn_id, $filename);
		ftp_put($conn_id, $filename, $flv, FTP_BINARY);
		ftp_site($conn_id, sprintf('CHMOD %u %s', 777, $filename));
		if ( !ftp_chdir($conn_id, '..') ) {
		    die('Failed to change directory to: ' .$ftp_root);
		}		
	}
	if (file_exists($iphone)) {
		if ( !ftp_chdir($conn_id, 'iphone') ) {
		    die('Failed to change directory to: iphone');
		}	
		$filename = basename($iphone);
		ftp_delete($conn_id, $filename);
		ftp_put($conn_id, $filename, $iphone, FTP_BINARY);
		ftp_site($conn_id, sprintf('CHMOD %u %s', 777, $filename));
		if ( !ftp_chdir($conn_id, '..') ) {
		    die('Failed to change directory to: ' .$ftp_root);
		}	
	}
	if (file_exists($hd)) {
		if ( !ftp_chdir($conn_id, 'h264') ) {
		    die('Failed to change directory to: hd');
		}	
		$filename = basename($hd);
		ftp_delete($conn_id, $filename);
		ftp_put($conn_id, $filename, $hd, FTP_BINARY);
		ftp_site($conn_id, sprintf('CHMOD %u %s', 777, $filename));
	   if ( !ftp_chdir($conn_id, '..') ) {
		    die('Failed to change directory to: ' .$ftp_root);
	    }	
	}else{
	   echo 'no hd:'.$hd;
	}
	ftp_close($conn_id);
}


?>
