<div class="footer">
	<footer>
        <div class="fcontainer">
            <div class="flogo"><a href="{$relative}/">
            	<img src="{$relative}/images/logo/logo.png"  alt="logo"></a></div>
            <div class="footer-panel">
                <ul class="navf">
                    <li><a href="{$relative}/">首页</a></li>
                     <li><a data-toggle="modal" href="#login-modal">登录</a></li>
                     <li><a href="{$relative}/signup" >注册</a></li>
                    <li><a href="{$relative}/static/advertise" >广告合作</a></li>    
                </ul>
                <div class="footer-desc"><strong>警告：</strong>{$site_name}只适合18岁或以上人士观看。本网站内容可能令人反感！切不可将本站的内容出售、出租、交给或借予年龄未满18岁的人士或将本网站内容向未满18岁人士出示、播放或放映。本站内容收录于世界各地，如果您发现本站的某些影片内容不合适，或者某些影片侵犯了您的的版权，请联系我们删除影片。</div>
            </div>
        </div>
    </footer>
	
</div>
</div>
<link href="{$relative_tpl}/iconfont/iconfont.css" rel="stylesheet">
<div>
<div style="height: 60px;"></div>
<div class="weui-tabbar">
	<a href="{$relative}/" class="{$pageHome}">
		<span class="icon iconfont icon-shouye"></span>
		<p>首页</p>
	</a>
	<a href="{$relative}/categories" class="{$pageVideos}">
		<span class="icon iconfont icon-video"></span>
		<p>视频</p>
	</a>
	<a href="{$relative}/albums" class="{$pageAlbums}">
		<span class="icon iconfont icon-tupian"></span>
		<p>图片</p>
	</a>
	<a>
		<span class="icon iconfont icon-tubiao1shuxiaoshuo"></span>
		<p>小说</p>
	</a>
	<a>
		<span class="icon iconfont icon-VIP"></span>
		<p>加入VIP</p>
	</a>
</div>

 </div>
 <script type="text/javascript" src="{$relative_tpl}/js/common_m.js"></script>
    <script src="{$relative_tpl}/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{$relative_tpl}/js/jquery.rotator-0.2.js"></script>
    <script type="text/javascript" src="{$relative_tpl}/js/jquery.avs-0.2.js"></script>	
    <script type="text/javascript" src="{$relative_tpl}/js/adv.js?v=5.0"></script>
	{if $view && !$video.embed_code}
		<!--<script src="{$base_url}/media/player/videojs/video-js-events.js"></script>-->			
	{/if}
	{if $g_signin == '1' || $fb_signin == '1'}
		<script type="text/javascript" src="{$relative_tpl}/js/jquery.load-apis.js"></script>	
	{/if}	
	<script>
	{literal}
			if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
		  var msViewportStyle = document.createElement('style')
		  msViewportStyle.appendChild(
			document.createTextNode(
			  '@-ms-viewport{width:auto!important}'
			)
		  )
		  document.querySelector('head').appendChild(msViewportStyle)
		}
	{/literal}
	</script>	
	{include file='../../../templates/backend/default/analytics/analytics.tpl'}	
</body>
</html>