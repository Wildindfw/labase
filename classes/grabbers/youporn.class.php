<?php
class VGrab_youporn
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_youporn() {
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
        preg_match('/watch\/(.*?)\//', $this->url, $matches);
		if ( isset($matches[1]) ) {
			$vid = $matches[1];
			return $vid;
		}
	}		
	
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
			$title = trim(str_replace(' - Free Porn Videos - YouPorn', '', $matches['1']));
			return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
		}
    }
	
	function getVideoDescription() {
        preg_match('/"description">(.*?)</', $this->page, $matches);
        if ( isset($matches['1']) ) {
			return trim(htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES));
        }
	}
	
	function getVideoTags() {
		preg_match_all('/<a href="\/porntags\/(.*?)">(.*?)</', $this->page, $matches_tag);
		if ( isset($matches_tag['1']) ) {
			$tag_links  = $matches_tag['2'];
			foreach ( $tag_links as $tag ) {					
				$tags[] = str_replace(" ", "-", $tag);
			}
			return implode(' ', $tags);
		}
	}

    function getVideoCategory() {
        preg_match('/Categories:<\/label>(.*?)<\/div>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
            $category_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $category_string, $matches_category);
            if ( isset($matches_category['1']) ) {
                $category_links  = $matches_category['1'];
                return implode(' ', $category_links);
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