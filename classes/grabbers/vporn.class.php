<?php
class VGrab_vporn
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_vporn() {
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
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
			$title = trim(str_replace(' - vPorn.com', '', $matches['1']));
			return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
		}
    }

	function getVideoDescription() {
        preg_match('/description" content="(.*?)"/', $this->page, $matches);
        if ( isset($matches['1']) ) {			
			return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
        }
	}
	
	function getVideoTags() {
		preg_match_all('/class="tags">(.*?)</', $this->page, $matches);
		if ( isset($matches['1']) ) {
			foreach($matches[1] as $v) {
				 $chr = mb_substr ($v, 0, 1, "UTF-8");
				 if (mb_strtolower($chr, "UTF-8") == $chr) {
					 $tags[] = $v;
				 }
			}
			return implode(' ', $tags);
		}
	}

    function getVideoCategory() {
		preg_match_all('/class="tags">(.*?)</', $this->page, $matches);
		if ( isset($matches['1']) ) {
			foreach($matches[1] as $v) {
				 $chr = mb_substr ($v, 0, 1, "UTF-8");
				 if (mb_strtolower($chr, "UTF-8") != $chr) {
					 $cat[] = $v;
				 }
			}
			return implode(' ', $cat);
		}
    }
 
    function getVideoUrl() {
		preg_match_all('/flashvars.videoUrl(.*?) = "(.*?)"/', $this->page, $matches);		
		foreach ($matches[2] as $k => $v) {
			if (strpos($v, '/') == false) {
				unset($matches[0][$k]);
				unset($matches[1][$k]);
				unset($matches[2][$k]);
			}
		}
		foreach ($matches[2] as $k => $v) {
			$videos[$matches[1][$k]] = $v;
		}
		return $videos;
    }
}

?>