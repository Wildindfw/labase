<?php

class VGrab_tube8
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_tube8() {
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
		$id_arr = explode('/',rtrim($this->url,'/'));
		$vid = end($id_arr);
		$vid = intval(ltrim($vid,'video'));
		return $vid;
	}	
	
    function getVideoTitle() {
		preg_match('/<h1 class="main-title"><span class="item">(.*?)<\/span>/', $this->page, $matches);
		if ( isset($matches['1']) ) {
			return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
		}
    }
	
	function getVideoDescription() {
        preg_match('/<strong>Description:<\/strong>(.*?)</', $this->page, $matches);
        if ( isset($matches['1']) ) {
			return trim(htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES));
        }
	}
	
	function getVideoTags() {
        preg_match('/<li class="tag-list">(.*?)<\/li>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
            $tag_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $tag_string, $matches_tag);
            if ( isset($matches_tag['1']) ) {
                $tag_links  = $matches_tag['1'];
                foreach ( $tag_links as $tag ) {					
                    $tags[] = str_replace(" ", "-", $tag);
                }
                return implode(' ', $tags);
            }
        }
	}

    function getVideoCategory() {
        preg_match('/<strong>Category: <\/strong>(.*?)<\/li>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
            preg_match('/">(.*?)<\/a>/', $matches['1'], $matches_category);
            if ( isset($matches_category['1']) ) {
                return trim($matches_category['1']);
            }
        }
    }
 
    function getVideoUrl() {
        if (preg_match_all('/quality":"(.*?)","videoUrl":"(.*?)"/', $this->page, $matches_url)) {
			foreach ($matches_url[2] as $k => $v) {
				$videos[$matches_url[1][$k]] = stripslashes($v);
			}			
			ksort($videos);
			return $videos;
		} else {
			return false;
		}
	}
}

?>