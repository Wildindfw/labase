<?php


class VGrab_empflix
{
    var $url;
    var $page;
    var $curl;
    var $vkey;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_empflix() {
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
		$id_arr  = explode('/video',$this->url);
		$id_str  = end($id_arr);
		$vid = intval(current(explode('.',$id_str)));
		$this->vkey = $vid;		
		return $vid;
	}	
    
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
			$title = trim(str_replace(' - EMPFlix.com', '', $matches['1']));
			return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
		}
    }
	
	function getVideoDescription() {
        preg_match('/itemprop="description">(.*?)</', $this->page, $matches);
        if ( isset($matches['1']) ) {
			return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
        }
	}
	
	function getVideoTags() {
		return '';
	}

    function getVideoCategory() {
        preg_match('/Categories:<\/span>(.*?)<\/div>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {			
            $category_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $category_string, $matches_category);
            if ( isset($matches_category['1']) ) {
                return implode(' ', $matches_category['1']);
            }
        }
    }
 
    function getVideoUrl() {
		$content = $this->curl->saveToString("https://player.empflix.com/video/".$this->vkey);					
		preg_match('/flashvars.config = escape\("(.*?)"\)/', $content, $matches);
		if ( isset($matches['1']) && $matches['1'] != '') {
			$config_url = "https:".$matches['1'];
			$stream = $this->curl->saveToString($config_url);
            $stream = trim($stream);
            $stream = str_replace("\n", '', $stream);
            $stream = str_replace("\r", '', $stream);
            $stream = preg_replace('/\s\s+/', ' ', $stream);	
			if (preg_match_all ('/<res>(.*?)<\/res> <videoLink><!\[CDATA\[(.*?)\]\]/', $stream, $matches_url)) {
				foreach ($matches_url[2] as $k => $v) {
					if (strpos($v, 'https:') !== false) {
						$videos[$matches_url[1][$k]] = $v;
					} else {
						$videos[$matches_url[1][$k]] = "https:".$v;
					}
				}
				return $videos;
			}
			if (preg_match_all ('/<videoLink><!\[CDATA\[(.*?)\]\]/', $stream, $matches_url)) {
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