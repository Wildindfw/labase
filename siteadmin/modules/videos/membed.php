<?php
defined('_VALID') or die('Restricted Access!');

require $config['BASE_DIR'].'/classes/curl.class.php';
require $config['BASE_DIR'].'/include/config.membed.php';
require $config['BASE_DIR']. '/classes/image.class.php';
require_once ($config['BASE_DIR']. '/include/function_thumbs.php');

Auth::checkAdmin();

// Defined embed width/height
define('E_WIDTH', 560);
define('E_HEIGHT', 340);

$width	 = (int) $config['img_max_width'];
$height	 = (int) $config['img_max_height'];

if (isset($_GET['debug'])) {
	$debug = mysql_real_escape_string($_GET['debug']);
} else {
	$debug = false;
}

$image   = new VImageConv();

$embed		= array('username' => 'anonymous', 'url' => '', 'category' => '', 'status' => 1);

$categories	= get_categories();
$defaut_category = reset($categories);

if (isset($_POST['membed_videos'])) {
	require $config['BASE_DIR'].'/classes/filter.class.php';
	require $config['BASE_DIR'].'/classes/validation.class.php';
	$filter		= new VFilter();
	$valid		= new VValidation();
	$url		= $filter->get('url');
	$username	= $filter->get('username');
	$category	= $filter->get('category');
	$status		= $filter->get('status');
	
	if ($url == '') {
		$errors[] 		= 'URL field cannot be left blank!';
	} else {
	    $parts = explode('/', str_replace(array('http://www.', 'http://', 'https://www.', 'https://'), '', $url));
        if (isset($parts['0'])) {
      		$site = $parts['0'];
            if (!isset($sites[$site])) {
          		$errors[] = 'Invalid url! Supported sites: '.implode(', ', $sites).'!';
            } else {
				$site			= $sites[$site];
				$embed['url']	= $url;
            }
        } else {
			$errors[] = 'Failed to get site identifier from url!';
		}
	}
	
	if ($username == '') {
		$errors[]	= 'Username field cannot be left blank!';
	} else {
		$rs = $conn->execute("SELECT UID FROM signup WHERE username = '".mysql_real_escape_string($username)."' LIMIT 1");
		if (!$conn->Affected_Rows()) {
			$errors[] = 'Username is not a valid username on this system!';
		} else {
			$user_id			= (int) $rs->fields['UID'];
			$embed['username'] 	= $username;
		}
	}
	
	if ($category != '') {
		$embed['category'] 	= (int) $category;
	}
	
	$embed['status'] = (int) $status;
	if (!$errors) {
		$membed_file	= $config['BASE_DIR'].'/classes/membed/'.$site.'.php';
		$membed_class	= 'MEmbed_'.$site;
		if (file_exists($membed_file) && is_file($membed_file)) {
			require $membed_file;
			$graber	= new $membed_class($url, $user_id, $category, $status, $debug);
			if ($graber->get_videos()) {
				$video_added	= $graber->video_added;
				$video_already	= $graber->video_already;
				$message		= 'Added '.$video_added.' videos!';
				if ($video_already !== 0) {
					$message .= ' '.$video_already.' videos are already added to your site!';
				}
				
				$messages[] = $message;
			} else {
				$errors	= array_merge($errors, $graber->errors);
			}
		} else {
			$errors[]	= 'Failed to load '.$site.' class file!';
		}
	}
}

function add_video($video) {
	global $categories, $category, $config, $conn, $width, $height, $image;
	$active_thumb = 1;
	if( count($video['thumbs']) > 4 ) {
		$active_thumb = 3;
	}
	if (!$category || $category == '0') {
		$category_video = match_category($video['category'], $video['title'], $video['description'], $video['tags']);
	} else {
		$category_video = $category;
	}
	if (!$video['tags']) {
		$video['tags'] = str_to_tags($video['title']);
	}
	$sql = "INSERT INTO video
		   SET UID = ".$video['user_id'].", 
		   title = '".mysql_real_escape_string($video['title'])."',  
		   description = '".mysql_real_escape_string($video['description'])."',  
		   keyword = '".mysql_real_escape_string($video['tags'])."',  
		   channel = ".$category_video.",  
		   duration = ".$video['duration'].",  
		   thumb = ".$active_thumb.",  
		   thumbs = ".(count($video['thumbs'])-1).",  				   
		   embed_code = '".mysql_real_escape_string($video['embed'])."',
		   addtime = ".time().",
		   adddate = '".date('Y-m-d')."',
		   vkey = '" .mt_rand(). "',
		   type = 'public',
		   active = '0'";			   
	$conn->execute($sql);
	
	if ($conn->Affected_Rows()) {
		$VID 		= mysql_insert_id();
		$thumb_dir  = get_thumb_dir($VID);
        $count      = 1;
        $valid      = 0;
		$curl = new VCurl();
		if (mkdir($thumb_dir)) {
      		foreach ($video['thumbs'] as $thumb) {
				$dst = $thumb_dir.'/'.$count.'.jpg';
          		if ($curl->saveToFile($thumb, $dst)) {
					if (filesize($dst) > 2000) {
						//-- Process Thumb - Aspect
						list($src_w, $src_h) = getimagesize($dst);
						$aspect     = $width / $height;
						$src_aspect = $src_w / $src_h;
						if ($aspect < $src_aspect) {
							$tmp_h = $height;
							$tmp_w = floor($tmp_h * $src_aspect);
							$image->process($dst, $dst, 'EXACT', $tmp_w, $tmp_h);
							$image->resize(true, true);
							$x = floor(($tmp_w - $width)/2);
							$y = 0;
						}
						else {
							$tmp_w = $width;
							$tmp_h = floor($tmp_w / $src_aspect);
							$image->process($dst, $dst, 'EXACT', $tmp_w, $tmp_h);
							$image->resize(true, true);
							$x = 0;
							$y = floor(($tmp_h - $height)/2);
						}
						$image->process($dst, $dst, 'EXACT', $width, $height);
						$image->crop($x, $y, $width, $height, true);				
						//-- Process Thumb - Aspect - END
						++$valid;
						++$count;
					}
					else {
						unlink($dst);
					}
				}
      		}
            if ($valid !== 0) {
				$vkey = substr(md5($VID),11,20);
				$conn->execute("UPDATE video SET active = '".$video['status']."', thumbs = ".$valid.", vkey = '".$vkey."' WHERE VID = ".$VID." LIMIT 1");
				$conn->execute("INSERT INTO mass_embedder SET site = '".$video['site']."', url = '".$video['url']."'");
                return true;
			}	
		}
	} else {
		return false;
	}
}

function clean_html($html) {
	$html   = str_replace(array("\n", "\r"), '', $html);
    $html   = preg_replace('/\s\s+/', ' ', $html);
	return $html;
}

function match_category($category, $title, $description, $tags) {

	global $categories, $defaut_category;

	$categories_arr = explode(',', $category);

	foreach ($categories_arr as $cat_video) {
		$cat_video = trim($cat_video);
		if ($cat_video != '') {
			foreach ($categories as $cat) {
				if (stripos($cat_video,$cat['name']) !== false or stripos($cat['name'],$cat_video) !== false) {
					return $cat['CHID'];
				}
			}
		}
	}

	foreach ($categories as $cat) {
		if (stripos($title,$cat['name']) !== false) {
			return $cat['CHID'];
		}
	}

	foreach ($categories as $cat) {
		if (stripos($description,$cat['name']) !== false) {
			return $cat['CHID'];
		}
	}

	foreach ($categories as $cat) {
		if (stripos($tags,$cat['name']) !== false) {
			return $cat['CHID'];
		}
	}

    return $defaut_category['CHID'];
}

function already_added($embed_code) {
	global $conn;
	$embed_code = str_replace (" ", "", $embed_code);
	$embed_code = str_replace ("'", "\"", $embed_code);		
	if (preg_match('/src="(.*?)"/', $embed_code, $matches)) {
		$sql = "SELECT * FROM video WHERE embed_code LIKE '%".mysql_real_escape_string($matches[1])."%' LIMIT 1";
		$conn->execute($sql);
		if ($conn->Affected_Rows() > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function duration_to_seconds($duration) {
	$duration = trim($duration);
	$duration = str_replace('h',':',$duration);	
	$duration = str_replace('m',':',$duration);
	$duration = str_replace('s','',$duration);
    $duration   = explode(':', $duration);
	if (count($duration) == 1) {
		return intval($duration[0]);
	} elseif (count($duration) == 2) {
		$minutes = intval($duration[0]);
		$seconds = intval($duration[1]);
		return (($minutes * 60) + $seconds);
	} elseif (count($duration) == 3) {
		$hours   = intval($duration[0]);
		$minutes = intval($duration[1]);
		$seconds = intval($duration[2]);
		return (($hours * 3600) + ($minutes * 60) + $seconds);
	} else {
		return false;
	}
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = ltrim($url, '//');
        $url = "http://" . $url;
    }
    return $url;
}

function str_to_tags($tags_str) {
	$tags       = array();
	$tags_arr   = explode(' ', strtolower(prepare_string($tags_str, false)));
	foreach ($tags_arr as $tag) {
		if (strlen($tag) >= 5) {
			$tags[] = $tag;
		}
	}
	if (!empty($tags)) {
		return implode(' ', $tags);
	} else {
		foreach ($tags_arr as $tag) {
			if (strlen($tag) >= 4) {
				$tags[] = $tag;
			}
		}
		if (!empty($tags)) {
			return implode(' ', $tags);
		} else {
			foreach ($tags_arr as $tag) {
				if (strlen($tag) >= 3) {
					$tags[] = $tag;
				}
			}
			return implode(' ', $tags);
		}
	}
}

$smarty->assign('debug', $debug);
$smarty->assign('sites', $sites);
$smarty->assign('embed', $embed);
$smarty->assign('categories', $categories);
?>