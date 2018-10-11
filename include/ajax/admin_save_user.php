<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR']. '/classes/filter.class.php';
require $config['BASE_DIR']. '/include/compat/json.php';
require $config['BASE_DIR']. '/include/adodb/adodb.inc.php';
require $config['BASE_DIR']. '/include/dbconn.php';

function nl2br2($string) { 
	$string = str_replace(array("\\r\\n", "\\r", "\\n"), "\n", $string);
	return $string; 
} 

$response = array('status' => 0);

$data = (array) json_decode(stripslashes($_POST['data']));

//Acount
$uid              = trim($data['id']);
$email            = trim($data['email']);
$emailverified    = trim($data['emailverified']);
$premium          = trim($data['premium']);
$account_status   = trim($data['account_status']);
$likes            = trim($data['likes']);
$dislikes         = trim($data['dislikes']);
$profile_viewed   = trim($data['viewnumber']);
$video_viewed     = trim($data['video_viewed']);
$watched_video    = trim($data['watched_video']);
$password         = trim($data['password']);
$password_confirm = trim($data['password_confirm']);
//Personal
$fname           = trim($data['fname']);
$lname           = trim($data['lname']);      
$gender          = trim($data['gender']);      
$relation        = trim($data['relationship']);      
$interested      = trim($data['interested']); 
//Location
$town            = trim($data['town']);     
$city            = trim($data['city']);     
$country         = trim($data['country']); 
//Profile    
$website         = trim($data['website']);     
$aboutme         = trim($data['aboutme']);     
$occupation      = trim($data['occupation']);     
$company         = trim($data['company']);     
$school          = trim($data['school']);
$interest_hobby  = trim($data['interest_hobby']);
$fav_movie_show  = trim($data['fav_movie_show']);
$fav_music       = trim($data['fav_music']);
$fav_book        = trim($data['fav_book']);
$turnon          = trim($data['turnon']);
$turnoff         = trim($data['turnoff']);

settype($uid, 'integer');
settype($profile_viewed, 'integer');
settype($video_viewed, 'integer');
settype($watched_video, 'integer');
settype($likes, 'integer');
settype($dislikes, 'integer');

if ( $likes != 0 || $dislikes !=0)
	$rate = round(($likes * 100)/($likes + $dislikes));
else
	$rate = 0;

$sql_add = NULL;  
if ( $password != '' ) {
	$passwd 	= md5($password);
	$sql_add 	= " ,pwd = '" .$passwd. "'";
}

$sql = "UPDATE signup SET fname = '" .mysql_real_escape_string($fname). "', lname = '" .mysql_real_escape_string($lname). "', 
						  premium = '".mysql_real_escape_string($premium)."', email = '" .mysql_real_escape_string($email). "', 
						  gender = '" .mysql_real_escape_string($gender). "', relation = '" .mysql_real_escape_string($relation). "',
					      interested = '" .mysql_real_escape_string($interested). "', 
						  aboutme = '" .nl2br2(mysql_real_escape_string($aboutme)). "', town = '" .mysql_real_escape_string($town). "', 
						  city = '" .mysql_real_escape_string($city). "', country = '" .mysql_real_escape_string($country). "', 
						  occupation = '" .nl2br2(mysql_real_escape_string($occupation)). "', company = '" .nl2br2(mysql_real_escape_string($company)). "',
						  school = '" .nl2br2(mysql_real_escape_string($school)). "', interest_hobby = '" .nl2br2(mysql_real_escape_string($interest_hobby)). "',
						  fav_movie_show = '" .nl2br2(mysql_real_escape_string($fav_movie_show)). "', fav_music = '" .nl2br2(mysql_real_escape_string($fav_music)). "', 
						  turnon = '" .nl2br2(mysql_real_escape_string($turnon)). "', turnoff = '" .nl2br2(mysql_real_escape_string($turnoff)). "',
						  fav_book = '" .nl2br2(mysql_real_escape_string($fav_book)). "', website = '" .mysql_real_escape_string($website). "',
						  video_viewed = '" .mysql_real_escape_string($video_viewed). "', profile_viewed = '" .mysql_real_escape_string($profile_viewed). "',
						  watched_video = '" .mysql_real_escape_string($watched_video). "', emailverified = '" .mysql_real_escape_string($emailverified). "',
						  likes = '" .mysql_real_escape_string($likes). "', dislikes = '" .mysql_real_escape_string($dislikes). "', 
						  rate = '" .mysql_real_escape_string($rate). "', account_status = '" .mysql_real_escape_string($account_status). "'" 
						  .$sql_add. " WHERE UID = '" .mysql_real_escape_string($uid). "' LIMIT 1";

$conn->execute($sql);
$response['status'] = 1;
echo json_encode($response);
die();
?>
