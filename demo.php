<?php

define('_VALID', true);
require 'include/config.php';
require 'include/function_global.php';
require 'include/function_smarty.php';

$smarty->display('demo_video.tpl');

$smarty->gzip_encode();

// phpinfo();
exit;
function memory_usage() {
    $memory	 = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
    return $memory;
}

//判断是否是最后一块，如果是则进行文件合成并且删除文件块
function fileMerge(){
    
    echo '开始内存：'.memory_usage();
    
    set_time_limit(300);
    ini_set('memory_limit','-1');
    
    $fileName = './media/videos/demo2/2g.mp4';
    //$fileName = './media/videos/text/0.txt';
    
    $blob = '';
    file_put_contents($fileName,$blob);
    exit;
    $arr = array();
    for($i=1; $i<= 2216; $i++){    
        $blob = file_get_contents($fileName.'__'.$i);        
        file_put_contents($fileName,$blob, FILE_APPEND);
        
    }
    //echo '运行后内存：'.memory_usage();
    //$blob = implode("", $arr);
    echo '运行后内存：'.memory_usage();
    //file_put_contents($fileName,$blob);
    $blob = '';
    echo '回到正常内存：'.memory_usage();
    exit;
}
define('_VALID', true);
require '/include/config.php';
require '/include/function_global.php';
require '/include/function_smarty.php';
require '/include/function_video.php';
require '/include/function_conversion.php';
require '/include/function_server.php'; 

function videos(){   
    
    
    $basedir = dirname(dirname(__FILE__));
    
    
    require  '/scripts/convert_videos_yh.php';
    
    $video_name= '4613.mp4';
    $vid= 4613;
    $video_path= 'D:/www/labase/media/videos/vid/4613.mp4';
    convert_videos($video_name,$vid,$video_path);
    
    
}

function video(){
    
    define('_VALID', true);
    require 'include/config.php';
    require 'include/function_global.php';
    require 'include/function_smarty.php';
    require 'classes/pagination.class.php';
    
    $smarty->display('demo_video.tpl');
   
    $smarty->gzip_encode();
}

video();
