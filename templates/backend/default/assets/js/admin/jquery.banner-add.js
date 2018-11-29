$(document).ready(function(){
	$("#adv_group").select2(); 
	
	$("#adv_starttime").datepicker({
		format: 'yyyy-mm-dd',
		startDate: new Date()
	});
	
	if($("#adv_id").length == 0){
		$("#adv_starttime").datepicker("setDate",new Date());
	}
	
	
	$("#adv_exptime").datepicker({
		format: 'yyyy-mm-dd',
		startDate: new Date()
	});
	
});