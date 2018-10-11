<?php

class VGrab_realgfporn
{
    var $url;
    var $page;
    var $curl;
	
    function __construct() {
        $this->curl = new VCurl();
    }

    function VGrab_realgfporn() {
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
        preg_match('/videoid = (.*?);/', $this->page, $matches);
        if ( isset($matches['1']) ) {			
			$vid = $matches['1'];
			return $vid;
        }
	}
    
    function getVideoTitle() {
        preg_match('/<title>(.*?)<\/title>/', $this->page, $matches);
        if ( isset($matches['1']) ) {
            $matches['1'] = str_replace(" - Real Girlfriend Porn","",$matches['1']);
			return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
        }
    }
	
	function getVideoDescription() {
        preg_match('/<meta itemprop="description" content="(.*?)"/', $this->page, $matches);
        if ( isset($matches['1']) ) {
			return htmlspecialchars_decode(strip_tags(stripslashes($matches['1'])), ENT_QUOTES);
        }
	}
	
	function getVideoTags() {
        preg_match('/Tags:(.*?)<br>/', $this->page, $matches);		
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
		return '';
    }
 
    function getVideoUrl() {
		preg_match('/source src=\'(.*?)\'/', $this->page, $matches);
		if ( isset($matches['1']) ) {
			 $videos['Default'] = $matches['1'];
			return $videos;
		} else
			return false;
    }
}

?>