
	
	$(function(){
		
		
		function resize(){
			var a = $("body").width() - $(".header-logo").innerWidth() - $(".header-signup").innerWidth();
		
			if(a > 140 ){
				$(".header-search").width(a-20)
			}
		}
		
		
		setTimeout(function(){
			resize()
		},1500);
		
	})
	
