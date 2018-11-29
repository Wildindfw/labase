<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>VIP充值</title>
		<link href="{$relative_tpl}/buy/form.css" rel="stylesheet" type="text/css">
		<link href="{$relative_tpl}/buy/csscp.css" rel="stylesheet" type="text/css">
		<script src="{$relative_tpl}/js/jquery-1.11.1.min.js"></script>	
	</head>

	<body>
		<div id="container">
			<!-- end of header -->
			<!--main-->
			<div id="main">

				<!--right-->
				<div id="header1">

					<div id="right_second">
						<form id="form" action="#" method="post" class="democss" target="_blank">
							<input type="hidden" name="host" value="99re.com" />
							<div id="infoDiv">
								<div id="a_btn">
								</div>

								<div id="card_detail">
									<center>
										<h3 style=" font-size:18px;">购买VIP会员 -  升级成功即可享受多种游戏特权！！</h3>
										<h4 style=" font-size:15px;"> <span style="color:red">由于网络波动，没有及时充值到帐的用户，请不要过分着急，联系我们客服会核对补上</span></h4>
									</center>
									<table class="tab04" border="0" cellpadding="0" cellspacing="0" width="99%">
										<tbody>

											<tr>
												<th scope="row" width="35%" height="25">
													<span class="red">*</span>您需要升级会员的用户名 <span class="red"></span></a>）：</th>
												<td width="65%">
													<input name="username" type="text" id="username" size="20" value="" class="fr_input02" /></td>
											</tr>

											<tr>
												<th scope="row" height="150px">

													<table>
														<tbody>
															<tr>
																<td rowspan="2"><span class="red">*</span>请选择会员的长度<span class="red"></span>：</td>
																<td><img src="{$relative_tpl}/buy/img/1.jpg" width="100" height="60" /></td>
																<td><img src="{$relative_tpl}/buy/img/2.jpg" width="100" height="60" /></td>
															</tr>
															<tr>
																<td><img src="{$relative_tpl}/buy/img/3.jpg" width="100" height="60" /></td>
																<td><img src="{$relative_tpl}/buy/img/4.jpg" width="100" height="60" /></td>
															</tr>

														</tbody>
													</table>

												</th>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadiving="0" style="text-align: left;">
														<tr id="tr1">
															<td>
																{section name=i loop=$vips}
																<input type="radio" name="buy_id" id="buy_id" value="{$vips[i].id}" checked="checked" /> {$vips[i].cost}元人民币({$vips[i].name})
																</br>
																{/section}	
																

															</td>
														</tr>

													</table>
												</td>
											</tr>

											<tr>
												<th scope="row" height="25" width="35%">

													<table>
														<tbody>
															<tr>
																<td><span class="red">*</span>请选择付款的类型<span class="red"></span>：</td>
																<td><img src="{$relative_tpl}/buy/img/1111.jpg" width="100" height="60" /></td>
																<td><img src="{$relative_tpl}/buy/img/2222.jpg" width="100" height="60" /></td>
															</tr>

														</tbody>
													</table>

												</th>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadiving="0" style="text-align: left;">

														<tr id="tr3" style="height: 60px;">

															<td>
																<input type="radio" class="pay" name="pw" value="AlipayApp" />支付宝
															</td>

															<td colspan="2" style="display:none">
																<input type="radio" class="pay" name="pw" value="QQApp" />QQ支付
															</td>

														</tr>

														<tr id="tr2" style="height: 60px">

															<td>
																<input type="radio" class="pay" name="pw" value="pd_alipay_pc" />支付宝充值
															</td>

															<td colspan="2" style="display:none">
																<input type="radio" class="pay" name="pw" value="pd_wxpay_pc" />微信扫码
															</td>

														</tr>

														<tr id="tr2" style="height: 60px">

															<td colspan="3">
																<span style="color:red">每笔订单的有效性是5分钟，超过5分钟未支付，请重新下单</span>
															</td>

														</tr>

													</table>
												</td>
											</tr>

											<tr>
												<th scope="row" height="65">&nbsp; </th>
												<td>
													<input name="button" id="button" onclick="return onFormSubmit();" value="购 买" class="btn_submit" type="submit">
													<input name="button" id="reset1" value="重 填" class="btn_reset" type="reset" onclick="ClearInfo()">
												</td>
											</tr>
										</tbody>
									</table>
									<b class="red">温馨提示：</b><br> 1、如果遇到充值成功但是VIP并没有加上的情况，请联系客服QQ:
									<a style="color:red" target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=888888&amp;site=qq&amp;menu=yes">888888</a>说明情况,并且提供您的用户名，购买方式等信息.<br>
									<br>

									<br>
									<b>
			<br>
		</b>
								</div>
							</div>

							

						</form>

					</div>
				</div>
			</div>
		</div>
	
		
		<script src="{$relative_tpl}/buy/buy.js"></script>
	</body>

</html>