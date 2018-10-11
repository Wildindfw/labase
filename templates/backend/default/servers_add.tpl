	<!-- BEGIN PAGE CONTAINER-->
	<div class="page-content"> 
		<div class="content">  
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<i class="icon-custom-left"></i>
				<h3>Servers - <span class="semi-bold">Add Servers</span></h3>
			</div>
			{include file="errmsg.tpl"}
			<!-- END PAGE TITLE -->
			<!-- BEGIN PlACE PAGE CONTENT HERE -->
			<div class="col-md-12">
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>Server <span class="semi-bold"> Details</span></h4>
					</div>
					<div class="grid-body no-border">
						<form class="form-no-horizontal-spacing" name="addServer" id="addServer" method="post" action="servers.php?m=add">
							<div class="row">
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">URL</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.url}error{/if}" name="url" type="text" value="{$server.url}">
												<span class="help">Eg. http://www.domain.com</span>
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">Video URL</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.video_url}error{/if}" name="video_url" type="text" value="{$server.video_url}">
												<span class="help">Eg. http://www.domain.com/media/videos</span>
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">FTP IP / Hostname</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.server_ip}error{/if}" name="server_ip" type="text" value="{$server.server_ip}">
												<span class="help">Eg. ftp.domain.com</span>
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">FTP Username</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.ftp_username}error{/if}" name="ftp_username" type="text" value="{$server.ftp_username}">
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">FTP Password</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.ftp_password}error{/if}" name="ftp_password" type="text" value="{$server.ftp_password}">
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>
								<div class="col-lg-6 col-lg-offset-3 col-md-12">
									<div class="row">		
										<div class="form-group">
											<label class="col-lg-4 control-label">FTP Path to Videos Directory</label>
											<div class="col-lg-8">
												<input class="form-control {if $err.ftp_root}error{/if}" name="ftp_root" type="text" value="{$server.ftp_root}">
												<span class="help">Eg. /public_html/media/videos</span>
											</div>
											<div class="clearfix"></div>
										</div>																		
									</div>
								</div>								
							</div>
							<div class="form-actions">
								<div class="pull-right">
									<input name="add_server" type="submit" value="Add Server" id="add_server" class="btn btn-success btn-cons">
									<a href="index.php" class="btn btn-white btn-cons">Cancel</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>			
			<!-- END PLACE PAGE CONTENT HERE -->
		</div>
	</div>
	<!-- END PAGE CONTAINER -->	

