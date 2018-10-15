<?php
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
?>