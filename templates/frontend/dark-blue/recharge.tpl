<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>VIP充值</title>		
		<link href="{$relative_tpl}/css/recharge.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="vipcard-box">
			<div class="vipcard-hd" style="position:relative">
				<span style="position: absolute;left:90px;top:30px;font-size:30px;">
    	<a style="color:white;" href="/" target="_blank">返回</a>
    	</span>
				<h1 class="layout">VIP充值</h1>
			</div>
			<div class="layout">

				<div class="vip">
					<div class="title">
						<h1>VIP特权</h1>
						<h2>今后将根据VIP会员反馈情况提供更多服务</h2>
					</div>
					<ul>
						<li><img src="{$relative_tpl}/img/recharge/v0.png" width="200" height="200" />
							<div class="txt"><br />
								<strong>1</strong>全站无广告
							</div>
						</li>
						<!--<li><img src="{$relative_tpl}/img/recharge/v2.png" width="200" height="200" /> <br />
							<div class="txt"> <strong>2</strong>观看视频无需积分</div>
						</li>-->
						<!--<li><img src="{$relative_tpl}/img/recharge/v4.png" width="200" height="200" />
							<div class="txt"> <strong><br />
                3</strong>所有视频可下载</div>
						</li>-->
						<li><img src="{$relative_tpl}/img/recharge/v3.png" width="200" height="200" />
							<div class="txt"> <strong><br />
                2</strong>VIP专属视频</div>
						</li>
						<li><img src="{$relative_tpl}/img/recharge/v1.png" width="200" height="200" />
							<div class="txt"> <strong><br />
                3</strong>视频高清模式切换</div>
						</li>
						<!--<li><img src="{$relative_tpl}/img/recharge/v5.png" width="200" height="200" />
							<div class="txt"> <strong>6</strong>VIP专属服务器及大陆电信直连带宽，全程流畅播放无卡顿。</div>
						</li>-->
					</ul>
					<div class="cler"></div>
				</div>

				<div class="clear"></div>

			</div>

			<form target="_blank" name="form" id="form" method="post" action="pay.php">
				<input type="hidden" name="user_id" value="0" />
				<input type="hidden" name="buy_id" id="buy_id" value="1" />
				<input type="hidden" name="host" id="host" value="99re.com" />
				<div class="slideTxtBox">
					<div class="hd" style="width:750px">

						<span class="arrow"><a class="next"></a><a class="prev"></a></span>
						<ul>
							{section name=i loop=$vips}
							<li val="{$vips[i].id}">{$vips[i].name}</li>
							{/section}	
						</ul>
					</div>
					<div class="bd">
						{section name=i loop=$vips start=0}
						<ul>
							<div class="vipcard-bd">
								<ul>									
									<li>
										<div class="listimgbox">
											<a href="javascript:void(0)"><img src="{$relative_tpl}/img/recharge/v{$smarty.section.i.index_next}.jpg" />
												<p class="listsubt"><em><strong>{$vips[i].duration}天</strong>期限<br /><strong>{$vips[i].cost}元</strong>价格<br /><strong>{$vips[i].describe}</strong></em></p>
											</a>
										</div>
									</li>
								</ul>
							</div>
						</ul>
						{/section}	
						<ul>

							<div class="vipcard-bd">
								<ul>
									<li>
										<div class="listimgbox">
											<a href="javascript:void(0)"><img src="{$relative_tpl}/img/recharge/v2.jpg" />
												<p class="listsubt"><em><strong>90天</strong>期限<br /><strong>138元</strong>价格<br /><strong>1.5元</strong>/天,充值后登陆网站送论坛邀请码</em></p>
											</a>
										</div>
									</li>

								</ul>
							</div>
						</ul>
						<ul>

							<div class="vipcard-bd">
								<ul>
									<li>
										<div class="listimgbox">
											<a href="javascript:void(0)"><img src="{$relative_tpl}/img/recharge/v3.jpg" />
												<p class="listsubt"><em><strong>180天</strong>期限<br /><strong>238元</strong>价格<br /><strong>1.3元</strong>/天,充值后登陆网站送论坛邀请码</em></p>
											</a>
										</div>
									</li>

								</ul>

							</div>
						</ul>
						<ul>

							<div class="vipcard-bd">
								<ul>
									<li>
										<div class="listimgbox">
											<a href="javascript:void(0)"><img src="{$relative_tpl}/img/recharge/v4.jpg" />
												<p class="listsubt"><em><strong>365天</strong>期限<br /><strong>365元</strong>价格<br /><strong>1元</strong>/天,充值后登陆网站送论坛邀请码</em></p>
											</a>
										</div>
									</li>

								</ul>
							</div>
						</ul>

						<ul>
							<div class="vipcard-bd">
								<ul>
									<li>
										<div class="listimgbox">
											<a href="javascript:void(0)"><img src="{$relative_tpl}/img/recharge/v0.jpg" />
												<p class="listsubt"><em><strong>10元,充值后登陆网站送论坛邀请码</strong></em></p>
											</a>
										</div>
									</li>

								</ul>
							</div>
						</ul>

					</div>
				</div>

				<div style="clear:both">
					<div class="vipcard-bd">
						<div class="pay" style="height:370px">
							<h3>付款成功后，直接关闭网页重新登录，可能会延迟几分钟。成功付款但是没有开通VIP的请联系QQ客服1059846781</h3>

							<div style="clear:both;height:10px"></div>

							<div style="clear:both;height:10px"></div>

							<div class="listinfos">
								<a href="/recharge.php?page=buy" target="_blank" class="nolmod-btn getbtn">立即充值</a>
							</div>

							<div style="clear:both;height:10px"></div>

							<div style="clear:both;height:10px"></div>

							<div style="clear:both;height:10px"></div>

						</div>

						<div class="listinfos">
							<div class="info">
								<h1>不会充值的请咨询在线客服　</h1>
								<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=888888&amp;site=qq&amp;menu=yes"> <img border="0" src="{$relative_tpl}/img/recharge/qq.png" alt="点击这里给我发消息" title="点击这里给我发消息"></a>
							</div>
						</div>
					</div>
				</div>
			</form>

		</div>
		
		<script src="{$relative_tpl}/js/jquery-1.11.1.min.js"></script>	
		<script src="{$relative_tpl}/js/jquery.SuperSlide.2.1.1.js"></script>	
		{literal}
		<script type="text/javascript">
			jQuery(".slideTxtBox").slide({trigger:"click"});
		</script>
		{/literal}
	</body>

</html>