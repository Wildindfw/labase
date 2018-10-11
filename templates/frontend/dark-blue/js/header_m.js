$(function() {

	var panel = $("#panel-group");

	$("#menu-btn").click(function() {

		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			panel.removeClass('show-menu');
		} else {
			$(this).addClass('active')
			panel.addClass('show-menu');
		}

	})

	$("#search-btn").click(function() {
		
		$(this).toggleClass('active')
		panel.toggleClass('showsearch')

	})

})