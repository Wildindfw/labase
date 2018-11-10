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
		VIDEO - m3u8
		<div id="play1" class="play">
			
		</div>
		<br />
		VIDEO - mp4
		<div id="play2" class="play">
			
		</div>
		
		
		
		<script>
			{literal}
			$(function(){
				
				$("#play1").append('<video id="roomVideo1" class="video-js vjs-default-skin" controls preload="none" ></video>');
				
				var w = 360;
				var h = 150;
				var myPlayer = videojs('roomVideo1',{
					autoplay:true,
		            poster: "http://vjs.zencdn.net/v/oceans.png",
		            height:h, 
    				width:w
		       });
		       myPlayer.src('http://videocdn2.quweikm.com:8091/20180801/TJKO2HY366/index.m3u8');
		       //http://v.medialaba.com:2280/media/videos/m3u8/20180220/HMadIqli/index.m3u8
		       //http://videocdn2.quweikm.com:8091/20180801/TJKO2HY366/index.m3u8
		       
		       
		       $("#play2").append('<video id="roomVideo2" class="video-js vjs-default-skin" controls preload="none" ></video>');
		       var myPlayer2 = videojs('roomVideo2',{
		       		autoplay:true,
		       		 height:h, 
    				width:w
		       });
		       myPlayer2.src('http://v.99hot.vip/video//m3u8/20181107/ab0d0ad8db8392a62a5a14d31ff4f903/index.m3u8');
		       //http://v.99hot.vip/video//m3u8/20181106/40eec463930ecf3f17b06b979a9ad637/index.m3u8
		       
			})	
			
			
			{/literal}
		</script>
		
		
	</body>

</html>