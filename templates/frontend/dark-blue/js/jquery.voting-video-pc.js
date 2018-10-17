$(document).ready(function(){            
    $("[id*='vote_']").click(function(event) {
    	$("#flagging_failure").hide()
        event.preventDefault();
        var vote_id     = $(this).attr("id");
        var id_split    = vote_id.split('_');
        var vote      = id_split[1];
        var item_id     = id_split[2];
        $.post(base_url + '/ajax/vote_video', { item_id: item_id, vote: vote },
            function (response) {
			if (response.msg =='') {
				if (response.likes != 0 || response.dislikes != 0) {
					
					$("#progress").html(`<span>${response.rate}% (${response.likes}/${response.dislikes})</span>
				            <div class="progress-bar">
				                <div class="indicator" style="width: ${response.rate}%;"></div>
				            </div>`);					
					
				}
			}
			else {
				$("#flagging_failure").show().html(response.msg);										
			}
        }, "json");            
    });
});

    

