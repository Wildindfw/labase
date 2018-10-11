<?php
class VGrab_spankwire
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_spankwire() {
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
		$vid = ltrim($vid,'video');
		$vid = current(explode('&',$vid));
		$vid = current(explode('?',$vid));
		return $vid;
	}
    
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
			$title = str_replace(" - Spankwire.com", "", $matches['1']);
            return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
        }
    }
	
	function getVideoDescription() {
		return '';
	}
	
	function getVideoTags() {
        preg_match('/catTagInfo">(.*?)<\/span>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {
            $tag_string = $matches['1'];
            preg_match('/class="tagged">(.*?)<\/a>/', $tag_string, $matches_tag);
            if ( isset($matches_tag['1']) ) {				
				$tag_stream  = $matches_tag['1'];
				$tags = explode("-", $tag_stream);
                return implode(' ', $tags);
            }
        }
	}

    function getVideoCategory() {
        preg_match('/catTagInfo">(.*?)<\/span>/', $this->page, $matches);		
        if ( isset($matches['1']) ) {			
            $category_string = $matches['1'];
            preg_match_all('/class="uploadedIn">(.*?)<\/a>/', $category_string, $matches_category);
            if ( isset($matches_category['1']) ) {
                return str_replace('&nbsp;','',implode(' ', $matches_category['1']));
            }
        }
    }
 
    function getVideoUrl() {
		if (preg_match_all ('/playerData.cdnPath(.*?) = \'(.*?)\';/', $this->page, $matches_url)) {
			foreach ($matches_url[2] as $k => $v) {
				if ($v != '' and strlen($matches_url[1][$k])<10) {
					$videos[$matches_url[1][$k].'p'] = 'http:'.str_replace('http:', '', $v);
				}			
			}
			return $videos;				
		} else {
			return false;
		}
    }
}

?>