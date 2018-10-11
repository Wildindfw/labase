<?php
define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';
echo "<h2 style='color:#222'>Multi Server addon for AVS version 2.</h2><hr>";
if($_POST['submit']){
	echo "<h3 style='color:red'>Updating database tables</h2>";
	// Update servers Table
	$videos = mysql_query("SELECT server_id, url FROM servers");
	echo "<h4 style='color:grey'>Updating Table 'servers'</h4> (To remove port numbers in url ex. :81 for lighttpd)<br/>";
	while(list($sid,$url) = mysql_fetch_row($videos)) {
		$newd = explode(":",$url);
		if(is_array($newd)){
			$news = $newd[0].':'.$newd[1];
		}else{
			$news = $url;
		}	
		$sql = "UPDATE servers SET url = '".$news."' WHERE server_id = '".$sid."' LIMIT 1";
		$conn->execute($sql);
		echo "Updated Server #".$sid." Changed \"url\" from <b>".$url."</b> to <b>".$news."</b> <br/>";
	}		
	// Update videos Table
	echo "<h4 style='color:grey'>Updating Table 'videos'</h4> (Changing field 'server' from current to 'video_url' value from table 'servers')<br/>";
	$videos = mysql_query("SELECT VID, server FROM video");
	while(list($vid,$server) = mysql_fetch_row($videos)) {
		if($server != ''){
			$sql = "SELECT video_url FROM servers WHERE url = '".$news."'";
			$rs = $conn->execute($sql);
			$new = $rs->fields['video_url'];
			$sql = "UPDATE video SET server = '".$new."' WHERE VID = '".$vid."' LIMIT 1";
			$conn->execute($sql);
			echo "Updated VID #".$vid." Changed \"server\" from <b>".$server."</b> to <b>".$new."</b> <br/>";
		}
	}
	echo "<h2 style='color:red'>Complete.</h2>";
}else{
	echo "<h3 style='color:red'>Backup your current AVS Database and press 'Update'.</h3>";
	echo "<h4 style='color:grey'>This will alter your existing database so be sure to backup to avoid any dataloss.</h4>";
	echo "<form action='updater.php' method='post'><input type='submit' name='submit' value='Update'/></form>";
}
?>
