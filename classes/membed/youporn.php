<?php
class MEmbed_youporn
{
	public $url;
	public $user_id;
	public $category;
	public $status;
	public $video;
	public $errors	= array();
	public $debug_e = array();
	public $debug_w = array();
	public $message;
	private $overflow = 500;
	public $video_already	= 0;
	public $video_added	= 0;
	public $debug;
	
	public function __construct($url, $user_id, $category, $status, $debug) {
		$this->url		= $url;
		$this->user_id	= $user_id;
		$this->category	= $category;
		$this->status	= $status;
		$this->debug	= $debug;
	}
	
	public function get_videos() {
		$count  = 0;
		$curl	= new VCurl();
        $html   = clean_html($curl->saveToString($this->url));		
		if ($html) {
			if (preg_match_all('/data-video-id="(.*?)<\/i><\/span><\/div><\/div>/', $html, $matches)) {
				foreach ($matches[0] as $match) {

					unset($debug_e);
					unset($debug_w);				
                    ++$count;
                    if ($count > $this->overflow) {
                        $this->errors[] = 'Overflow reached (500)! Aborting!';
                        return false;
                    }
					
					$video  = array(
						'user_id'	  => $this->user_id,
						'status'	  => $this->status,						
						'site'        => 'youporn',
						'id'		  => '',
						'embeddable'  => true,						
						'url'         => '',
						'title'       => '',
						'description' => '',
						'tags'        => '',
						'category'    => '',
						'thumbs'      => array(),
						'duration'    => 0,
						'embed'       => ''	
                    );
					
					//Video ID
					if (preg_match('/data-video-id="(.*?)"/', $match, $matches_id)) {
						$video['id'] = trim($matches_id[1]);
					} else {
						if (!$this->debug) continue;
						else $debug_e[] = 'ID';
					}
					
					//URL
					if(preg_match('/data-video-id="(.*?)"/', $match, $matches_url)) {
						$video['url'] = "https://www.youporn.com/watch/" . trim($matches_url[1]);
					} else {
						$this->errors[]	= 'Failed to get video URL for ID: '.$video['id'].'!';
						if (!$this->debug) continue;
						else $debug_e[] = 'URL';
					}
					
					//Embed Code
					$embed_url = str_replace('/watch/', '/embed/', $video['url']);
					$video['embed'] = "<iframe src='" . $embed_url . "' frameborder=0 height='" . E_HEIGHT . "' width='" . E_WIDTH . "' scrolling=no name='yp_embed_video'></iframe>";					
					if (already_added($video['embed'])) {
						++$this->video_already;
						continue;
					}

					//Title
					if(preg_match('/alt=\'(.*?)\'/', $match, $matches_title)) {
						$video['title']	= htmlspecialchars_decode(strip_tags(stripslashes($matches_title[1])), ENT_QUOTES);
					} else {
						$this->errors[]	= 'Failed to get video title for '.$video['url'].'!';
						if (!$this->debug) continue;
						else $debug_e[] = 'TITLE';
					}

					//Duration
					if(preg_match('/duration">(.*?)</', $match, $matches_duration)) {
                        $video['duration'] = duration_to_seconds($matches_duration[1]);
                    } else {
						$this->errors[]	= 'Failed to get video duration for '.$video['url'].'!';
						if (!$this->debug) continue;
						else $debug_e[] = 'DURATION';
					}
					
					//Thumbnails
					if (preg_match('/data-thumbnail="(.*?)"/', $match, $matches_thumb)) {
						
						if (strpos($thumb_url, '/original/') !== false) { 
							$thumb_url = $matches_thumb[1];
							$thumb_url = explode('/original/', $thumb_url);
							$thumb_url = $thumb_url[0].'/original/';
							for ($i=1;$i<=16;$i++) {
								$video['thumbs'][] = $thumb_url. $i . '/1.jpg';
							}
						} elseif (strpos($thumb_url, ')') !== false) { 
							$thumb_url = $matches_thumb[1];
							$thumb_url = explode(')', $thumb_url);
							$thumb_url = $thumb_url[0]. ')' .$thumb_url[1].')';
							for ($i=1;$i<=16;$i++) {
								$video['thumbs'][] = $thumb_url. '.' . $i . '.jpg';
							}							
						} else {
							$parts = explode('/', $matches_thumb[1]);
							$last = array_pop($parts);
							$parts = array(implode('/', $parts), $last);
							$thumb_url = $parts[0];
							for ($i=1;$i<=16;$i++) {
								$video['thumbs'][] = $thumb_url. '/' . $i . '.jpg';
							}								
						}
                    } else {
						$this->errors[]	= 'Failed to get video thumbnails for '.$video['url'].'!';
						if (!$this->debug) continue;
						else $debug_e[] = 'THUMBS';	
					}

					//Get Video Page
					$html_video	= clean_html($curl->saveToString($video['url']));
					
					//Categories
					if(preg_match('/<label>Categories:<\/label>(.*?)<\/div>/', $html_video, $matches_category)) {
						if (preg_match_all('/href="\/category\/(.*?)">(.*?)<\/a>/', $matches_category[1], $matches_cat)) {
							$video['category'] = implode(',', $matches_cat[2]);
						} else {
							$debug_w[] = 'CATEGORY';
						}
					} else {
						$debug_w[] = 'CATEGORY';
					}
					
					//Tags
					if (preg_match_all('/<a href="\/porntags\/(.*?)">(.*?)<\/a>/', $html_video, $matches_tags)) {
						$video['tags'] = implode(' ', str_replace(' ','-',$matches_tags[2]));
						$video['tags'] = strtolower($video['tags']);
					} else {
						$debug_w[] = 'TAGS';
					}

					//Check Embeddable Content
					$video['embeddable'] = true;
					
					//Debug Mode
					if ($this->debug) {
						echo "Match Content (". $count ."): <textarea style='width:100%' rows=10>".$match."</textarea><br>";						
						if ($debug_e) echo "Errors: " . implode(', ',$debug_e) . "<br>";
						if ($debug_w) echo "Warnings: " . implode(', ',$debug_w) . "<br>";
						echo "<pre>";
						print_r($video);
						echo "</pre>";
						exit;
					}
					
					//Add Video
					if (add_video($video)) {
						++$this->video_added;
					} else {
						$this->errors[] = 'Failed to add '.$video['url'].'!';
					}					
					
				} //Foreach Loop - END
			} else {
				$this->errors[] = 'Failed to find embeddable videos on the specified page!';
			}
		} else {
			$this->errors[] = 'Failed to get html code for specified url!';
		}		
		if (!$this->errors) {
			return true;
		}
		return false;
	}
}
?>