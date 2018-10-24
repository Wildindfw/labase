function jsnull() {
	return
}
var player=null;
var dtime=null;
$(document).ready(function($) {
			
//		if(/Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent)){
//			yhVideo();
//			return false;
//		}		

		
		if($(window).width() < 600){
			getPlayerAdv();
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
        //poster: "封面",
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

function getPlayerAdv(){
				
		
		var w = $(window).width(),h = w/16*9;
		
		$("#video_adv").css({
			width:w,
			height:h
		});	
				
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
							$("#video_adv").remove();
							player = new ckplayer(videoObject);
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