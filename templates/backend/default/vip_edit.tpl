<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none;">
	<div id="editModalDialog" class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 id="editModalLabel" class="semi-bold"><span id="edit-type-span-1"></span> 等级 <span id="edit-id-span"></span>: Edit</h4>
			</div>
			<div class="modal-body">		
				<div class="row form-row">									
					<input id="edit-id" name="edit-id" type="hidden" value=""/>
					<label class="col-lg-3 control-label">名称</label>
					<div class="col-lg-9">
						<input id="edit-name" name="name" type="text" value="" class="form-control">
					</div>
					<div class="clearfix"></div>
					<div id="edit-slug-container">
						<label class="col-lg-3 control-label">价格</label>
						<div class="col-lg-9">
							<input id="edit-cost" name="cost" type="text" value="" class="form-control m-0">
							<span class="help"></span>
						</div>
						<div class="clearfix"></div>
					</div>					
					<div id="edit-slug-container">
						<label class="col-lg-3 control-label">描述</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="edit-describe" name="describe" rows="3"></textarea>
							<span class="help"></span>
						</div>
						<div class="clearfix"></div>
					</div>										
					<div class="m-b-10"></div>				
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