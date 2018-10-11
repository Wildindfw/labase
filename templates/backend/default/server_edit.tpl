<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none;">
<div id="editModalDialog" class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 id="editModalLabel" class="semi-bold">Server <span id="edit-id-span"></span>: Edit</h4>
		</div>
		<div class="modal-body">		
			<div class="row form-row">									
				<input id="edit-id" name="edit-id" type="hidden" value=""/>
				<label class="col-lg-3 control-label">URL</label>
				<div class="col-lg-9">
					<input id="edit-url" name="edit-url" type="text" value="" class="form-control" placeholder="Eg. http://www.domain.com">
				</div>
				<div class="clearfix"></div>
				<label class="col-lg-3 control-label">Video URL</label>
				<div class="col-lg-9">
					<input id="edit-video_url" name="edit-video_url" type="text" value="" class="form-control" placeholder="Eg. http://www.domain.com/media/videos">
				</div>
				<div class="clearfix"></div>
				<label class="col-lg-3 control-label">FTP IP / Hostname</label>
				<div class="col-lg-9">
					<input id="edit-server_ip" name="edit-server_ip" type="text" value="" class="form-control" placeholder="Eg. ftp.domain.com">
				</div>
				<div class="clearfix"></div>				
				<label class="col-lg-3 control-label">FTP Username</label>
				<div class="col-lg-9">
					<input id="edit-ftp_username" name="edit-ftp_username" type="text" value="" class="form-control">
				</div>
				<div class="clearfix"></div>
				<label class="col-lg-3 control-label">FTP Password</label>
				<div class="col-lg-9">
					<input id="edit-ftp_password" name="edit-ftp_password" type="text" value="" class="form-control">
				</div>
				<div class="clearfix"></div>
				<label class="col-lg-3 control-label">FTP Path to Videos Directory</label>
				<div class="col-lg-9">
					<input id="edit-ftp_root" name="edit-ftp_root" type="text" value="" class="form-control" placeholder="Eg. /public_html/media/videos">
				</div>
				<div class="clearfix"></div>				
				<div class="m-t-10"></div>	
				<label class="col-lg-3 control-label">Currently Used</label>
				<div class="col-lg-9">
					<div class="radio p-t-9">
						<input id="edit-current_used_y" type="radio" name="edit-current_used" value="1" class="radio-enabled">
						<label for="edit-current_used_y">Yes</label>
						<input id="edit-current_used_n" type="radio" name="edit-current_used" value="0" class="radio-disabled">
						<label for="edit-current_used_n">No</label>
					</div>
				</div>					
				<div class="clearfix"></div>					
				<label class="col-lg-3 control-label">Status</label>
				<div class="col-lg-9">
					<div class="radio p-t-9">
						<input id="edit-status_a" type="radio" name="edit-status" value="1" class="radio-enabled">
						<label for="edit-status_a">Active</label>
						<input id="edit-status_i" type="radio" name="edit-status" value="0" class="radio-disabled">
						<label for="edit-status_i">Inactive</label>												
					</div>
				</div>					
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			<button type="button" id="edit-reset" class="btn btn-white btn-icon">Reset</button>
			<button type="button" id="edit-save" class="btn btn-success">Save</button>
		</div>
	</div>
 
</div>
 
</div>	