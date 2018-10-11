<?php
class VGrab_pornhub
{
    var $url;
    var $page;
    var $curl;
    var $vkey;	
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_pornhub() {
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
		$id_arr = explode('viewkey=',$this->url);
		$id_str = end($id_arr);
		$vid = current(explode('&',$id_str));
		$this->vkey = $vid;
		return $vid;
	}	
    
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
			$title = trim(str_replace(' - Pornhub.com', '', $matches['1']));
			return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
		}
    }
	
	function getVideoDescription() {
        return '';
	}
	
	function getVideoTags() {
        preg_match('/Tags:&nbsp;(.*?)<span>/', $this->page, $matches);		
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
        preg_match('/Categories:&nbsp;(.*?)<span>/', $this->page, $matches);
        if ( isset($matches['1']) ) {			
            $category_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $category_string, $matches_category);
            if ( isset($matches_category['1']) ) {
                return implode(' ', $matches_category['1']);
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