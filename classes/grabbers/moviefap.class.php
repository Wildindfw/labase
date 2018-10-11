<?php
class VGrab_moviefap
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_moviefap() {
        $this->__construct();
    }

    function getPage($url) {
        $this->url  = $url;
        if ( $this->page = $this->curl->saveToString($url) ) {
            $this->page = trim($this->page);
            $this->page = str_replace("\n", '', $this->page);
            $this->page = str_replace("\r", '', $this->page);
            $this->page = preg_replace('/\s\s+/', ' ', $this->page);
            return true;
        }
        return false;
    }

	function getVideoID() {
        preg_match('/videos\/(.*?)\//', $this->url, $matches);
		if ( isset($matches[1]) ) {
			$vid = $matches[1];
			return $vid;
		}
	}
    
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
			$title = trim(str_replace(', MovieFap.com', '', $matches['1']));
			return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
		}
    }
	
	function getVideoDescription() {
		return '';
	}
	
	function getVideoTags() {
		return '';
	}

    function getVideoCategory() {
        preg_match('/<div id="vid_info">(.*?)<\/div> <\/div>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {	
            $category_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $category_string, $matches_category);
			array_shift($matches_category['1']);
            if ( isset($matches_category['1']) ) {
                return implode(' ', $matches_category['1']);
            }
        }
    }
 
    function getVideoUrl() {
		preg_match('/flashvars.config = escape\("(.*?)"\)/', $this->page, $matches);
		if ( isset($matches['1']) && $matches['1'] != '') {
			$config_url = $matches['1'];
			$stream = $this->curl->saveToString($config_url);
            $stream = trim($stream);
            $stream = str_replace("\n", '', $stream);
            $stream = str_replace("\r", '', $stream);
            $stream = preg_replace('/\s\s+/', ' ', $stream);
			if (preg_match_all ('/<res>(.*?)<\/res> <videoLink>(.*?)<\/videoLink>/', $stream, $matches_url)) {
				foreach ($matches_url[2] as $k => $v) {
					if (strpos($v, 'https:') !== false) {
						$videos[$matches_url[1][$k]] = $v;
					} else {
						$videos[$matches_url[1][$k]] = "https:".$v;
					}
				}			
				return $videos;
			}
			if (preg_match_all ('/<videoLink>(.*?)<\/videoLink>/', $stream, $matches_url)) {
				$video_url = end($matches_url['1']);
					if (strpos($video_url, 'https:') !== false) {
						$videos['Default'] = $video_url;
					} else {
						$videos['Default'] = "https:".$video_url;
					}
				return $videos;
			} else {
				return false;
			}
		}
    }
}

?>