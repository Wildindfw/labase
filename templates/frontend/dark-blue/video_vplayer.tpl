{if $video.embed_code != ''}
	<div class="video-embedded">
		{$video.embed_code}
	</div>
{else}
<style>
.yytf_1 {ldelim}
	position: absolute;
	z-index: 100;
	height: 500px;
	width: 100%;
	left:0px;
	top:0px;
	background-color: #000;
{rdelim}
#daojs {ldelim}
	text-align: right;
	background-color: #000;
	padding-right: 20px;
	color: #FFF;
	padding-top: 10px;
	padding-bottom: 20px;
	height: 25px;
	line-height: 8px;
{rdelim}
</style>
{literal}
	<style type="text/css">
		@media (min-width: 1200px){
			.vidvid{
				width: 100%;
				height: 422px;
			}
			.vid-ads{
          		height:390px !important;

            }
		}
		@media (min-width: 992px){
			.vidvid{
				width: 100%;
				height: 422px;
			}
          	.video-dimensions{
				height: 420px;
			}
          	.vid-ads{
          		height:390px !important;
            }
		}
		@media (width: 768px){
			.vidvid{
				width: 100%;
				height: 360px;
			}
		}
		@media only screen and (max-device-width:480px){
			.video-dimensions{
				height: 260px;
			}
      	}      	
      	.adv_count_down{ 
      		position: absolute; right: 10px;top: 10px; background: #000000; color: #FFFFFF;
      		padding:5px 15px;font-size:14px;
      	}
	</style>
{/literal}

<script type="text/javascript" src="{$relative_tpl}/js/ckplayer/ckplayer.js"></script>
<div class="video-container">
	<div id="video" class="vidvid" style="width: 100%;"></div>
	<div id="video_adv" style="background: #000000;">		
	</div>
	<script type="text/javascript">
		var videoObject = {ldelim}
		container: '#video', //容器的ID或className
		variable: 'player',//播放函数名称
		flashplayer:false,
		autoplay:true,
		poster:'material/poster.jpg',//封面图片
		advertisements:'/ckplayer.php',//广告动态获取
		video: [//视频地址列表形式
			{section name=i loop=$video.files}
				['{$video.files[i].file}', '', '{if $video.files[i].height == 1080}蓝光SD{/if}{if $video.files[i].height == 720}超清{/if}{if $video.files[i].height == 480}高清{/if}{if $video.files[i].height == 360}标清{/if}{if $video.files[i].height == 360}普清{/if}', 0],
			{/section}
				]
		{rdelim};
		
	</script>
	<script type="text/javascript" src="{$relative_tpl}/js/my_video.js"></script>
</div>
{/if}
