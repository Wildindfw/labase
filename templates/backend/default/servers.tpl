	{include file="server_edit.tpl"}
	<!-- BEGIN PAGE CONTAINER-->
	<div class="page-content"> 
		<div class="content">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<i class="icon-custom-left"></i>
				<h3>Servers - <span class="semi-bold">Manage Servers</span></h3>
			</div>
			{include file="errmsg.tpl"}
			<!-- END PAGE TITLE -->
			<!-- BEGIN PlACE PAGE CONTENT HERE -->															
			<div class="col-md-12">
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>Manage <span class="semi-bold">Servers</span></h4>
					</div>
					<div class="grid-body no-border">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-no-horizontal-spacing search-filters" name="search_servers" method="POST" action="servers.php?m={$module}">
									<input id="sort" name="sort" type="hidden" value={$option.sort}>
									<input id="order" name="order" type="hidden" value={$option.order}>
									<div class="pull-left">
										<div class="btn-group"> <a class="btn dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#">Order by <span id="sort_items">{if $option.sort == 'last_used'}Last Used{else}ID{/if}</span> <span class="caret"></span> </a>
											<ul class="dropdown-menu">
												<li><a href="#" onClick="document.getElementById('sort_items').innerText = 'ID'; document.getElementById('sort').value = 'server_id'" >ID</a></li>
												<li><a href="#" onClick="document.getElementById('sort_items').innerText = 'Last Used'; document.getElementById('sort').value = 'last_used'" >Last Used</a></li>
											</ul>
										</div>									
										<div class="btn-group"> <a class="btn dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#"><span id="order_items">{if $option.order == 'ASC'}Ascending{else}Descending{/if}</span> <span class="caret"></span> </a>
											<ul class="dropdown-menu">
												<li><a href="#" onClick="document.getElementById('order_items').innerText = 'Ascending'; document.getElementById('order').value = 'ASC'" >Ascending</a></li>
												<li><a href="#" onClick="document.getElementById('order_items').innerText = 'Descending'; document.getElementById('order').value = 'DESC'" >Descending</a></li>
											</ul>
										</div>
									</div>
									<div class="pull-right">
										<button type="button" id="reset_search" name="reset_search" class="btn btn-white btn-cons btn-icon"><i class="fa fa-times"></i></button>									
										<button type="submit" name="search_servers" class="btn btn-success btn-cons btn-icon m-r-0"><i class="fa fa-search"></i></button>									
									</div>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
						<!-- END SEARCH FILTERS -->						
						<div class="row">
							<div class="col-xs-12">
								<div>
									{if $servers}
										{section name=i loop=$servers}
											<div id="item-{$servers[i].server_id}" class="item-main-container small-thumb server">
												<div class="item-col-left">
													<div class="item-main">
														<div class="item-thumb">
															<div class="thumb-overlay">																	
																<img id="thumb-{$servers[i].server_id}" src="{$baseurl}/templates/backend/default/assets/img/server.png" class="img-responsive">																
																<div class="item-id">
																	<b>ID</b> {$servers[i].server_id}
																</div>																
															</div>												
														</div>
													</div>
												</div>
												<div class="item-col-right">
													<div class="item-details">
														<div class="row">						
															<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
																<div class="d-label">URL</div>
																<span id="url-{$servers[i].server_id}">{$servers[i].url}</span>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
																<div class="d-label">Status</div>
																<span id="status-{$servers[i].server_id}">
																	{if $servers[i].status == 1}
																		<span class="text-green" alt="Active" title="Active">Active</span>
																	{else}
																		<span class="text-red" alt="Inactive" title="Inactive">Inactive</span>
																	{/if}
																</span>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
																<div class="d-label">Currently Used</div>
																<span id="used-{$servers[i].server_id}">
																	{if $servers[i].current_used == 1}
																		<span class="text-info" alt="Enabled" title="Enabled">Yes</span>
																	{else}
																		<span class="text-red" alt="Disabled" title="Disabled">No</span>
																	{/if}
																</span>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
																<div class="d-label">Last Used</div>
																{if $servers[i].last_used != '0000-00-00 00:00:00'}
																	{$servers[i].last_used|date_format}
																{else}
																	N/A
																{/if}
															</div>
														</div>
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="item-actions">																									
													<div class="btn-group">
														<div class="btn-group">
															<a id="delete__server_{$servers[i].server_id}" class="btn btn-success" data-toggle="dropdown" href="#" alt="Delete" title="Delete"><i class="fa fa-trash-o"></i></a>
															<ul class="dropdown-menu">
																<li><a id="delete_server_{$servers[i].server_id}" href="#">Delete</a></li>
															</ul>
														</div>
														<a id="edit_server_{$servers[i].server_id}" class="btn btn-success" href="#" alt="Edit" title="Edit"><i class="fa fa-pencil"></i></a>
														{if $servers[i].current_used == 1}
															<a id="used_server_{$servers[i].server_id}" class="btn btn-success" href="#" alt="Disable Usage" title="Disable Usage" data-processing-used="0" data-used="1"><i class="fa fa-times-circle-o"></i></a>
														{else}
															<a id="used_server_{$servers[i].server_id}" class="btn btn-success" href="#" alt="Enable Usage" title="Enable Usage" data-processing-used="0" data-used="0"><i class="fa fa-check-circle-o"></i></a>
														{/if}													
														{if $servers[i].status == '1'}
															<a id="status_server_{$servers[i].server_id}" class="btn btn-success" href="#" alt="Suspend" title="Suspend" data-processing="0" data-status="1"><i class="fa fa-times"></i></a>
														{else}
															<a id="status_server_{$servers[i].server_id}" class="btn btn-success" href="#" alt="Activate" title="Activate" data-processing="0" data-status="0"><i class="fa fa-check"></i></a>
														{/if}																	
													</div>
												
												</div>												
											</div>
										{/section}									

									{else}
									<div class="row">
										<div class="col-xs-12">
											<div class="alert alert-info">
												<button class="close" data-dismiss="alert"></button>
												No Servers Found
											</div>
										</div>
									</div>
									{/if}	
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</div>			
			<!-- END PLACE PAGE CONTENT HERE -->
		</div>
	</div>
	<!-- END PAGE CONTAINER -->	