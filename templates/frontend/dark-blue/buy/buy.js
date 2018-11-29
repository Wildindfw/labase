function ClearInfo() {
	document.getElementById('username').value = '';
	document.getElementById('buy_id').value = '';
}

function onFormSubmit() {
	var val = $(":radio.pay:checked").val();
	if(!val) {
		alert('请选择支付方式');
		return false;
	}
	var txtUserName = document.getElementById('username');
	var p3_Amt = document.getElementById('amount');
	if(txtUserName.value == "") {
		txtUserName.focus();
		alert('请输入帐号！（用户名）');
		return false;
	}
	return true;
}

$(function() {
	$(":radio.pay1").click(function() {
		var val = $(this).val();

		if(val == 'one_alipay_pc') {
			$("#form").attr('action', './vip/payone/pay.php');
		}

		if(val == 'pd_alipay_pc') {
			$("#form").attr('action', './vip/paypd/index.php');
		}

		if(val == 'pd_wxpay_pc') {
			$("#form").attr('action', './vip/paypd/index.php');
		}

		if(val == 'QQpay') {
			$("#form").attr('action', 'http://pay99.shengshundj.com/hzf/scan/index.php');
		}

		if(val == 'Wy') {
			$("#form").attr('action', 'http://pay99.shengshundj.com/hzf/b2c/index.php');
		}

		if(val == 'AlipayApp') {
			$("#form").attr('action', './vip/paytw/payapp.php');
		}

		if(val == 'QQApp') {
			$("#form").attr('action', './vip/paytw/payapp.php');
		}

		if(val == 'wpay') {
			$("#form").attr('action', './vip/paytw/pay.php');
		}

		if(val == 'alipay') {
			$("#form").attr('action', './vip/paytw/pay.php');
		}

		if(val == "purealipay") {
			$("#form").attr('action', 'http://pay99.alaichaye.cn/pay.php');

		}

		if(val == 'hbs_alipay') {
			$("#form").attr('action', './vip/payhbs/index.php');
		}

		if(val == 'hbs_wxpay') {
			$("#form").attr('action', './vip/payhbs/index.php');

		}

	});
})