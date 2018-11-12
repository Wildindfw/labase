function loadCss(src){
    var cssTag = document.getElementById('loadCss');
    var head = document.getElementsByTagName('head').item(0);
    if(cssTag) head.removeChild(cssTag);
    css = document.createElement('link');
    css.href = src;
    css.rel = 'stylesheet';
    css.type = 'text/css';
    css.id = 'loadCss';
    head.appendChild(css);
}

function jsnull() {
	return
}
var player=null;
var dtime=null;
$(document).ready(function($) {
			
		if(false && /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent)){
			loadCss("/media/player/video/video.css");
			$("head").append('<script type="text/javascript" src="/media/player/video/video.min.js"></script>');
			$("head").append('<script type="text/javascript" src="/media/player/video/videojs-contrib-hls.js"></script>');
			yhVideo();
			return false;
		}		
		
		if(videoObject.uvip == "1"){
			videoObject.advertisements = "";
		}
		
		if($(window).width() < 600){
			getPlayerAdv(function(){
				player = new ckplayer(videoObject);
			});
		}else{
			player = new ckplayer(videoObject);
		}
		
		return true;
		$('.yytf_1').show();
		$('.djs').html('5');
		if(dtime!=null){
			window.clearTimeout(dtime);
		}
		dtime=window.setTimeout('timejian()',1000);
});
function timejian(){
	var time=parseInt($('.djs').html());
	if(time>0){
		time--;
		$('.djs').html(time+'');
		if(dtime!=null){
			window.clearTimeout(dtime);
		}
		window.setTimeout('timejian()',1000);
	}
	else{
		$('.yytf_1').hide();
		if(player!=null){
			player.playOrPause();
		}
	}
}

var MacPlayer = {
	Adv:{}
};

function loadedHandler(){}

function yhVideo(){
	
	var MacPlayer = {
		Adv:{}
	};	
	
	getPlayerAdv(function(){
		
		var w = $(window).width(),h = w/16*9;
		
		var playUrl = videoObject.video[0][0];
	   
		
		$("#video").append(`<video id="roomVideo1" class="video-js vjs-big-play-centered" controls preload="none" ><source id="source" src="${playUrl}" type="application/x-mpegURL"></video>`);
	
	
		var myPlayer = videojs('roomVideo1',{
			autoplay:false,
	        height:h, 
			width:w
	  	});
	   
	   
	   console.log(videoObject.video)
	   //$("#adv_count_down").text('('+ti+'s)');
	   
	   //playUrl  = "http://videocdn2.quweikm.com:8091/20180801/TJKO2HY366/index.m3u8";
	   myPlayer.src(playUrl);
	   
	});
	
	return false;
	
	//getPlayerAdv();
	
	var mp = $("#video"),w = mp.width(), h = mp.height();
	
	if(w < 100){
		w = $(window).width();
	}
	if(h < 100){
		h = 250;
	}
	
	//alert(`w:${w};h:`+h);
	
	mp.css({
		height:h, 
		width:w
	});
	
	
	$("#video").append(`<video id="roomVideo1" class="video-js vjs-big-play-centered" controls preload="none" ></video>`);
	
	
	var myPlayer = videojs('roomVideo1',{
		autoplay:false,
        height:h, 
		width:w
  },function(){
  		//暂停
		this.on('pause', function() {
		   $("#ad_pause").show();
		});
   });
   
   var playUrl = videoObject.video[0][0];
   console.log(playUrl);
   myPlayer.src(playUrl);
   
   
   	//关闭广告
   	$("#video").delegate(".ad_close","click",function(){
	 	$("#ad_front").hide();
	 	$("#ad_pause").hide();
   		myPlayer.play();
	});   	
	
	if(MacPlayer.Adv.front){
		var ts = MacPlayer.Adv.front.time;
		setTimeout(function(){
			$("#video .ad_close").click();   	
		},(ts * 1000));
	}	
   
   
   $(window).resize(function(){
   		w = mp.width();
   		h = mp.height();
   		myPlayer.width(w);
   		myPlayer.height(h);
   });
}

function getPlayerAdv(fn){
				
		
		var w = $(window).width(),h = w/16*9;
		
		$("#video_adv").css({
			width:w,
			height:h
		});	
		
		if(videoObject.uvip == "1"){
			if(fn && $.isFunction(fn)){
				fn();
			}
			$("#video_adv").hide();
			return false;
		}
				
		$.ajax({
			type:"get",
			url:"/ckplayer.php",
			dataType:"json",
			success:function(ret){				
				MacPlayer.Adv = {
					front:ret.front[0],
					pause:ret.pause[0]
				};
				
				if(MacPlayer.Adv.front){
					
					var ti = parseInt(MacPlayer.Adv.front.time);
					
					$("#video_adv").append(`<div id="ad_front" style="" class="ad_box">
						<div><span id="adv_count_down" class="adv_count_down"></span><a target="_blank" href="${MacPlayer.Adv.front.link}"><img src="${MacPlayer.Adv.front.file}" /></a></div>						
					</div>`);
					
					var aaa = setInterval(function(){
						$("#adv_count_down").text('('+ti+'s)');
						ti--;
						if(ti < 0){
							clearInterval(aaa);
							$("#video_adv").hide();
							if(fn && $.isFunction(fn)){
								fn();
							}
							//player = new ckplayer(videoObject);
						}
					},1000);					
				}
				
				if(MacPlayer.Adv.pause){
					$("#video_adv").append(`<div id="ad_pause" style="display: none;" class="ad_box">
						<div><a target="_blank" href="${MacPlayer.Adv.pause.link}"><img src="${MacPlayer.Adv.pause.file}" /></a></div>
						<span href="javascript:;" class="ad_close"><span class="ad_close_s"></span></span>
					</div>`);
				}
				
				$("#video_adv img").css({
					"max-height":h, 
					"max-width":w
				});
				
				
			}
		});
		
	}