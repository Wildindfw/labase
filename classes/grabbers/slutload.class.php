<?php

class VGrab_slutload
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_slutload() {
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
		return $vid;
	}
	
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
			$title = str_replace(" - Slutload.com", "", $matches['1']);
            return htmlspecialchars_decode(strip_tags(stripslashes($title)), ENT_QUOTES);
        }
    }
	
	function getVideoDescription() {
		return '';
	}
	
	function getVideoTags() {
		preg_match_all('/<a rel="tag"(.*?)">(.*?)<\/a>/', $this->page, $matches_tag);
		if ( isset($matches_tag['2']) ) {
			$tag_links  = $matches_tag['2'];
			foreach ( $tag_links as $tag ) {					
				$tags[] = str_replace(" ", "-", $tag);
			}
			return implode(' ', $tags);
		}
	}

    function getVideoCategory() {
		return '';
    }
 
    function getVideoUrl() {
		preg_match('/data-url="(.*?)"/', $this->page, $matches);
		if ( isset($matches['1']) ) {
			 $videos['Default'] = $matches['1'];
			return $videos;
		} else
			return false;
    }
}

?>