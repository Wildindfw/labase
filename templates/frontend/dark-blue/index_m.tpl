
{literal}
<script type="text/javascript">
    $(document).ready(function() {
        $("#hided").click(function(){
	        $("#divad").hide();
	    });
	    $("#tago").click(function(){
	        $("#divad").hide();
	    });
    });
</script>
<style type="text/css">
	@media (min-width: 1200px){
		.col-lg-2, .col-md-2, .col-lg-3, .col-md-3 {
		    padding: 0 !important; 
		}
		.col-lg-2, .col-md-2{
			width: 20% !important;
		}
	}
	@media only screen and (max-device-width:480px){
       .list-group-item{
      		display:none !important;
        } 
    }
	.well-sm {
		padding: 5px !important;
	}
	@media (max-width: 600px){
		.container{ padding: 0;}
		.ad-body{ width: 100%; padding: 0 !important;}
		.container .well-filters{ padding: 0 9px !important;}
	}
</style>

{/literal}

<div class="container">

	<div class="well ad-body">
		{insert name=adv assign=adv group='index_top'}
		{if $adv}{$adv}{/if}
	</div>

	<div class="well well-filters">
		<div class="pull-left">
			<h4>{translate c='index.videos_being_watched'}</h4>
		</div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{$relative}/videos?o=bw"><span class="hidden-xs"><i class="fa fa-plus"></i> {translate c='index.videos_being_watched_more'}</span><span class="visible-xs"><i class="fa fa-plus"></i></span></a>
		</div>		
		<div class="clearfix"></div>
      	
      	<div class="time-view" id="sort_viewtime">
			<a href="javascript:;" data-sort="bw">按播放次数</a>
			<span class="separate"></span>
			<a href="javascript:;" data-sort="mv">按最受欢迎</a>
			<span class="separate"></span>
			<a href="javascript:;" data-sort="lg">按片长</a>
		</div>
      	<div class="clearfix"></div>
      	
	</div>

	<div class="row">
		<div class="col-md-9 col-sm-8">
            {if $viewed_videos}
			<div class="row" id="sort_viewtime_orw">
				
            {section name=i loop=$viewed_videos}
				<div class="col-sm-4 col-md-3 col-lg-3">
					<div class="well well-sm">
						<div class="heart-heart video-rating pull-left {if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}no-rating{/if}">
							<i class="fa fa-heart video-rating-heart {if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}no-rating{/if}"></i> <b>{if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}-{else}{$viewed_videos[i].rate}%{/if}</b>
						</div>	
						<a href="{$relative}/video/{$viewed_videos[i].VID}/{$viewed_videos[i].title|clean}">
							<div class="thumb-overlay">
								<img src="{if $viewed_videos[i].thumb_img == '0'}{insert name=thumb_path vid=$viewed_videos[i].VID}/{$viewed_videos[i].thumb}.jpg{else}{insert name=thumb_path }/{$viewed_videos[i].thumb_img}{/if}" title="{$viewed_videos[i].title|escape:'html'}" alt="{$viewed_videos[i].title|escape:'html'}" {if $viewed_videos[i].thumb_img == '0'}id="rotate_{$viewed_videos[i].VID}_{$viewed_videos[i].thumbs}_{$viewed_videos[i].thumb}_viewed"{/if}   class="img-responsive {if $viewed_videos[i].type == 'private'}img-private{/if}"/>
								{if $viewed_videos[i].type == 'private'}<div class="label-private">{t c='global.PRIVATE'}</div>{/if}
								{if $viewed_videos[i].hd==1}<div class="hd-text-icon">HD</div>{/if}
								<div class="duration">
									{insert name=duration assign=duration duration=$viewed_videos[i].duration}
									{$duration}
								</div>
							</div>
							<span class="video-title title-truncate m-t-5">{$viewed_videos[i].title|escape:'html'}</span>
						</a>
						<div class="video-views pull-left">
							{$viewed_videos[i].viewnumber} {if $viewed_videos[i].viewnumber == '1'}{t c='global.view'}{else}{t c='global.views'}{/if}
						</div>
						<div class="video-added pull-right">
							{insert name=time_range assign=addtime time=$recent_videos[i].addtime}
							{$addtime}
						</div>
						<div class="clearfix"></div>
						
					</div>				
				</div>			
            {/section}
            
			</div>
			<div class="view-more" id="view_more">
            	<span>更多</span>
            </div>
            {else}
			<div class="well well-sm">
				<span class="text-danger">{t c='videos.no_videos_found'}.</span>
			</div>
            {/if}			
						

		</div>
		
		<div class="col-md-3 col-sm-4">
			<div class="well ad-body">
				{insert name=adv assign=adv group='index_right'}
				{if $adv}{$adv}{/if}
			</div>
			<div class="list-group tago">
				<a href="/videos" class="list-group-item active">
					All
				</a>
				<a href="/videos/vip" class="list-group-item">
					VIP会员专区
				</a>
				<a href="/videos/vr" class="list-group-item">
					VR专区
				</a>
				<a href="/videos/subtitle" class="list-group-item">
					中文字幕
				</a>
				<a href="/videos/zipai" class="list-group-item">
					偷拍自拍
				</a>
				<a href="/videos/cartoon" class="list-group-item">
					动漫卡通
				</a>
				<a href="/videos/gay" class="list-group-item">
					同性系列
				</a>
				<a href="/videos/uncover" class="list-group-item">
					日韩无码
				</a>
				<a href="/videos/cover" class="list-group-item">
					日韩有码
				</a>
				<a href="/videos/oumei" class="list-group-item">
					欧美电影
				</a>
				<a href="/videos/av" class="list-group-item">
					独家AV
				</a>
				<a href="/videos/3" class="list-group-item">
					综合三级片
				</a>
				<a href="/videos/zhubo" class="list-group-item">
					韩国女主播
				</a>
			</div>
		</div>
	</div>
	
	<div class="well ad-body">
		{insert name=adv assign=adv group='index_center'}
		{if $adv}{$adv}{/if}
	</div>

	<div class="well well-filters">
		<div class="pull-left">
			<h4>{translate c='index.most_recent_videos'}</h4>
		</div>
		<div class="pull-right">
			<a class="btn btn-primary" href="{$relative}/videos?o=mr"><span class="hidden-xs"><i class="fa fa-plus"></i> {translate c='index.most_recent_videos_more'}</span><span class="visible-xs"><i class="fa fa-plus"></i></span></a>
		</div>		
		<div class="clearfix"></div>
	</div>

	<div class="row">
		<div class="col-sm-12">
            {if $recent_videos}
			<div class="row">
            {section name=i loop=$recent_videos}
				<div class="col-sm-5 col-md-2 col-lg-2">
					<div class="well well-sm">
						<div class="heart-heart video-rating pull-left {if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}no-rating{/if}">
							<i class="fa fa-heart video-rating-heart {if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}no-rating{/if}"></i> <b>{if $viewed_videos[i].rate == 0 && $viewed_videos[i].dislikes == 0}-{else}{$viewed_videos[i].rate}%{/if}</b>
						</div>	
						<a href="{$relative}/video/{$recent_videos[i].VID}/{$recent_videos[i].title|clean}">
							<div class="thumb-overlay">
								<img src="{if $recent_videos[i].thumb_img !='0'}{insert name=thumb_path}/{$recent_videos[i].thumb_img}{else}{insert name=thumb_path vid=$recent_videos[i].VID}/{$recent_videos[i].thumb}.jpg{/if}" title="{$recent_videos[i].title|escape:'html'}" alt="{$recent_videos[i].title|escape:'html'}"  {if $recent_videos[i].thumb_img == '0'}id="rotate_{$recent_videos[i].VID}_{$recent_videos[i].thumbs}_{$recent_videos[i].thumb}_viewed"{/if}   class="img-responsive {if $recent_videos[i].type == 'private'}{/if}"/>
								{if $recent_videos[i].type == 'private'}<div class="label-private">{t c='global.PRIVATE'}</div>{/if}
								{if $recent_videos[i].hd==1}<div class="hd-text-icon">HD</div>{/if}
								<div class="duration">
									{insert name=duration assign=duration duration=$recent_videos[i].duration}
									{$duration}
								</div>
							</div>
							<span class="video-title title-truncate m-t-5">{$recent_videos[i].title|escape:'html'}</span>
						</a>
						<div class="video-views pull-left">
							{$recent_videos[i].viewnumber} {if $recent_videos[i].viewnumber == '1'}{t c='global.view'}{else}{t c='global.views'}{/if}
						</div>
						<div class="video-added pull-right">
							{insert name=time_range assign=addtime time=$recent_videos[i].addtime}
							{$addtime}
						</div>
						<div class="clearfix"></div>
						
					</div>				
				</div>			
            {/section}
			</div>
            {else}
			<div class="well well-sm">
				<span class="text-danger">{t c='videos.no_videos_found'}.</span>
			</div>
            {/if}			
		</div>
	</div>
	
	<div class="well ad-body">
		{insert name=adv assign=adv group='index_bottom'}
		{if $adv}{$adv}{/if}
	</div>
</div>


{literal}
<script type="text/javascript">
	
	
   $(function(){
   		var page = {
   			o:"br",
   			p:1,
   			size:12
   		};
   		
   		var loa = '<div id="view_loading" class="view-loading">正在加载...</div>';
   		
   		//排序
   		$("#sort_viewtime a").click(function(){   			
   			page.o = $(this).data('sort');
   			page.p = 1;
   			$("#sort_viewtime_orw").prepend(loa);
   			ajaxList("view");   			
   		});
   		//更多
   		$("#view_more").click(function(){
   			page.p++;
   			$("#sort_viewtime_orw").append(loa);
   			$("#view_more").hide();
   			ajaxList("view");   			
   		});
   		
   		
   		function ajaxList(t){   			
   			
   			$.ajax({
   				type:"get",
   				url:"/asyn.php",
   				dataType:"json",
   				data:{
   					"t":t,
   					"p":page.p,
   					"o":page.o,
   					"s":page.size
   				},
   				success:function(data){
   					$("#view_loading").remove();
   					if(data.count > 0){
   						var list = data.datas,html = "";
   						for (var i = 0; i < list.length; i++) {
   							html += viewHtml(list[i]);
   						}   
   						if(page.p > 1){
   							$("#sort_viewtime_orw").append(html);
   						}else{
   							$("#sort_viewtime_orw").html(html);
   						}   						
   					}
   					
   					if(data.count == page.size){
   						$("#view_more").show();
   					}
   					
   				}
   			});
   		}
   		
   		function viewHtml(data){   			
   			return `<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="well well-sm">
					<div class="heart-heart video-rating pull-left no-rating">
						<i class="fa fa-heart video-rating-heart no-rating"></i> <b>-</b>
					</div>	
					<a href="/video/${data.VID}/${data.title}">
						<div class="thumb-overlay">
							<img src="${data.thumb_img}" alt="${data.title}" class="img-responsive " />
						`+						
						(data.hd == 1 ? '<div class="hd-text-icon">HD</div>':'')						
						+						
						`
							<div class="duration">${data.duration}</div>
						</div>
						<span class="video-title title-truncate m-t-5">${data.title}</span>
					</a>
					<div class="video-views pull-left">
						${data.viewnumber} 观看次数	</div>
					<div class="video-added pull-right">						
						${data.addtime}
					</div>
					<div class="clearfix"></div>					
				</div>				
			</div>`;
   			
   			
   		}
   })
</script>

{/literal}