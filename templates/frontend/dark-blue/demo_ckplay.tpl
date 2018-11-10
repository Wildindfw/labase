<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>videojs</title>
		<script src="{$relative_tpl}/js/jquery-1.11.1.min.js"></script>
		
		
		
		<link href="/media/player/video/video.css" rel="stylesheet">
		<script src="/media/player/video/video.min.js"></script>
		<script src="/media/player/video/videojs-contrib-hls.js"></script>
		
	</head>

	<body>
		ckplayer - L
		<div id="play1" class="play">
			
		</div>
		<br />
		ckplayer - 视频服务器
		<div id="play2" class="play">
			
		</div>
		<br />
		ckplayer - web服务器
		<div id="play3" class="play">
			
		</div>
		
		
		<script src="{$relative_tpl}/js/ckplayer/ckplayer.min.js"></script>
		<script>
			{literal}
			$(function(){
				
				var videoObject = {
					container: '#play1', //容器的ID或className
					variable: 'player', //播放函数名称				
					autoplay: true,
					video: "http://www.lucaowan.com/upload/vod/XtW9GFNV/index.m3u8"				
				};
				
				//http://v.medialaba.com:2280/media/videos//m3u8/20180713/QcSywq2h/index.m3u8
				
				new ckplayer(videoObject);
				
				videoObject.container = "#play2";
				videoObject.video = "http://v.medialaba.com:2280/media/videos//m3u8/20180713/QcSywq2h/index.m3u8";
				
				new ckplayer(videoObject);
				
				videoObject.container = "#play3";
				videoObject.video = "http://www.bbgtod.com/cache/XtW9GFNV/index.m3u8";
				
				new ckplayer(videoObject);
				
				//http://img.ksbbs.com/asset/Mon_1703/05cacb4e02f9d9e.mp4
		        //myPlayer2.src('http://www.lucaowan.com/upload/vod/XtW9GFNV/index.m3u8');
		       
		       //http://v.medialaba.com:2280/media/videos//m3u8/20180713/QcSywq2h/index.m3u8
		       
			})	
			
			
			
			{/literal}
		</script>
		
		
	</body>

</html>