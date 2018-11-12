function validateString(input,ml) {
	$(input).change(function(){
		if($.trim($(this).val()).length < ml) {
			$(this).addClass('error');
		} else {
			$(this).removeClass('error');
		}
	});	
}

function validateNumber(input,dv) {
	$(input).change(function(){
		var iv = parseInt($(this).val().match(/\d+/));
		if (isNaN(iv)) {
			iv = dv;
		}
		$(this).val(iv);
	});
}



function hasErrors(input) {
	var err = false;
	$(input).each(function(){		
		if($(this).hasClass('error')) {
			err = true;
		}
	});
	return err;
}



$(document).ready(function(){
		
	$( "#reset_search" ).click(function() {
		document.getElementById('sort_items').innerText = 'ID';
		if (category_section == 'video') {
			document.getElementById('sort').value = 'CHID';
		} else if (category_section == 'album') {
			document.getElementById('sort').value = 'CID';			
		} else if (category_section == 'game') {
			document.getElementById('sort').value = 'category_id';
		}
		document.getElementById('order_items').innerText = 'Descending';
		document.getElementById('order').value = 'DESC';
		
	});	

	//Ajax:

	//Delete
    $("body").on('click', "a[id*='delete_vip_']", function(event) {
        event.preventDefault();	
		var id = $(this).data('id');
		$('#delete__vip_' + id).html('<i class="small-loader"></i>');
		$('#' + id).html('<i class="small-loader"></i>');
		$.post(base_url + '/ajax/admin_delete_vip', {  id: id },
			function (response) {
				if (response.status) {
					Messenger().post({
						message: '等级 <b>ID ' + id + '</b>: Successfully deleted!',
						type: 'success'
					});
					$('#item-' + id).fadeOut();
				} else {
					Messenger().post({
						message: '等级 <b>ID ' + id + '</b>: Delete failed!',
						type: 'error'
					});					
				}
		}, "json"); 
	});

	//Edit Category
    $("body").on('click', "a[id*='edit_vip_']", function(event) {
        event.preventDefault();
		var id = $(this).data('id');
		var name = $("#name-" +id).text();
		var cost = $("#cost-" +id).text();
		var describe = $("#describe-" +id).text();
		

		$('#edit-id').val(id);
		$('#edit-name').val(name);		
		$('#edit-cost').val(cost);
		$('#edit-describe').val(describe);
		
		
		//Reset Errors
		$('.form-control').each(function(){
			$(this).removeClass('error');
		});		
		
		//Adjust margin left to integer value - Center
				
		$('#editModal').modal('show');
		
	});	

	//Reset
	$("body").on('click', "button[id='edit-reset']", function(event) {
        event.preventDefault();
		var id = $('#edit-id').val();

		//Reset Errors
		$('.form-control').each(function(){
			$(this).removeClass('error');
		});
		
		var name = $("#name-" +id).text();
		var cost = $("#cost-" +id).text();
		var describe = $("#describe-" +id).text();
		

		$('#edit-name').val(name);		
		$('#edit-cost').val(cost);
		$('#edit-describe').val(describe);		
				
	});	
	
	//Edit Save
	$("body").on('click', "button[id='edit-save']", function(event) {		
		event.preventDefault();
		var id   = $('#edit-id').val();
		var name = $('#edit-name').val();
		var cost = $('#edit-cost').val();
		var describe = $('#edit-describe').val();
				
		if (!hasErrors("input[id*='edit-']")) {
			//save code
			$('#edit_vip_' + id).html('<i class="small-loader"></i>');			
			var categoryData = {
				id 		     : id,
				name	     : name,
				cost		 : cost,
				describe 	 : describe
			};
			var jsonCategoryData = JSON.stringify(categoryData);
			
			$.post(base_url + '/ajax/admin_save_vip', { data: jsonCategoryData },
				function (response) {					
					$('#editModal').modal('hide');
					if (response.status) {						
						Messenger().post({
							message: '等级 <b>ID ' + id + '</b>: Successfully updated!',
							type: 'success'
						});
						$('#name-' + id).text(response.name);
						$('#cost-' + id).text(response.cost);
						$('#describe-' + id).text(response.describe);
						
						if (response.total != null) {
							$('#total-items-' + id).text(response.total);
						}
					} else {
						Messenger().post({
							message: ' <b> ' + response.errors + '</b>',
							type: 'error'
						});
					}
					$('#edit_vip_' + id).html('<i class="fa fa-pencil"></i>');	
			}, "json");			
		}
		
	});

	

	
});