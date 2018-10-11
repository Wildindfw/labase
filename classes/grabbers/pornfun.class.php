<?php
class VGrab_pornfun
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_pornfun() {
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
            return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
        }
    }
	
	function getVideoDescription() {
        preg_match('/<meta name="description" content="(.*?)"/', $this->page, $matches);
        if ( isset($matches['1']) ) {
            return $matches['1'];
        }
	}
	
	function getVideoTags() {
        preg_match('/<span>Tags:<\/span>(.*?)<\/div>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
            $tag_string = $matches['1'];
            preg_match_all('/<a href="(.*?)">(.*?)<\/a>/', $tag_string, $matches_tag);
            if ( isset($matches_tag['2']) ) {
                foreach ( $matches_tag['2'] as $tag ) {					
                    $tags[] = strtolower(str_replace(" ", "-", $tag));
                }
                return implode(' ', $tags);
            }
        }
	}

    function getVideoCategory() {
        preg_match('/<span>categories:<\/span>(.*?)<\/div>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {			
            $category_string = $matches['1'];
            preg_match_all('/">(.*?)<\/a>/', $category_string, $matches_category);
            if ( isset($matches_category['1']) ) {
                return implode(' ', $matches_category['1']);
            }
        }
    }
 
    function getVideoUrl() {
		preg_match('/video_url: \'(.*?)\'/', $this->page, $matches);
		if ( isset($matches['1']) ) {
			 $videos['Default'] = $matches['1'];
			return $videos;
		} else
			return false;
    }
}

?>