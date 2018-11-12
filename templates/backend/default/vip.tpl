	{include file="vip_edit.tpl"}
	<!-- BEGIN PAGE CONTAINER-->
	<div class="page-content"> 
		<div class="content">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<i class="icon-custom-left"></i>
				<h3><span class="semi-bold">Vip 设置</span></h3>
			</div>
			{include file="errmsg.tpl"}
			<!-- END PAGE TITLE -->
			<!-- BEGIN PlACE PAGE CONTENT HERE -->															
			<div class="col-md-12">
				<div class="grid simple">	
					<div class="grid-title no-border">
						<h4>Vip <span class="semi-bold">设置</span></h4>
					</div>
					<div class="grid-body no-border">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-no-horizontal-spacing form-grey" name="add_vip" method="POST" enctype="multipart/form-data" action="vip.php">
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-3">
											<div class="form-group">
												 <input type="text" name="name" value="{$vip.name}" class="form-control {if $err.name}error{/if}" placeholder="名称">												
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-3">
											<div class="form-group">
												 <input type="text" name="cost" value="{$vip.cost}" class="form-control {if $err.slug}error{/if}" placeholder="价格">
											</div>
										</div>
																				
										<div class="col-xs-12 col-sm-6 col-md-3">
											<div class="form-group">
												<input type="submit" name="add_vip" value="添加等级" class="btn btn-success btn-cons btn-icon m-0 pull-right">
												<div class="clearfix"></div>
											</div>
										</div>
									</div>			
								</form>
							</div>
						</div>
						<!-- END SEARCH FILTERS -->						
						<div class="row">
							<div class="col-xs-12">
								<div>
									{if $vips}
										<form class="form-no-horizontal-spacing" name="category_select" method="post" id="category_select" action="">
										{section name=i loop=$vips}
											<div id="item-{$vips[i].id}" class="item-main-container category">
												<div class="item-col-left">
													<div class="item-main">
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
																<div class="d-label">ID</div>
																<span id="id-{$vips[i].id}">{$vips[i].id}</span>
															</div>
													</div>
												</div>
												<div class="item-col-right">
													<div class="item-details">
														<div class="item-title">
															<a>
																<span class="text-info" id="name-{$vips[i].id}">{$vips[i].name}</span>
															</a>
														</div>
														<div class="row">						
															<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
																<div class="d-label">价格</div>
																<span id="cost-{$vips[i].id}">{$vips[i].cost}</span>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
																<div class="d-label">描述</div>
																<span id="describe-{$vips[i].id}">{$vips[i].describe}</span >
															</div>
														</div>
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="item-actions">																									
													<div class="btn-group">
														<div class="btn-group">
															<a id="delete__category_{$vips[i].id}" class="btn btn-success" data-toggle="dropdown" href="#" alt="Delete" title="Delete"><i class="fa fa-trash-o"></i></a>
															<ul class="dropdown-menu">
																<li><a id="delete_vip_{$vips[i].id}" data-id = "{$vips[i].id}" href="#">Delete</a></li>
															</ul>
														</div>
														<a id="edit_vip_{$vips[i].id}" class="btn btn-success" href="#" alt="Edit" title="Edit" data-id="{$vips[i].id}"
															><i class="fa fa-pencil"></i></a>
																								
													</div>
												
												</div>												
											</div>
										{/section}									
										</form>
									{else}
									<div class="row">
										<div class="col-xs-12">
											<div class="alert alert-info">
												<button class="close" data-dismiss="alert"></button>
												没有VIP等级
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