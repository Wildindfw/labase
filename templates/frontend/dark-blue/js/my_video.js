function jsnull() {
	return
}
var player=null;
var dtime=null;
$(document).ready(function($) {
		player = new ckplayer(videoObject);
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
function loadedHandler(){}