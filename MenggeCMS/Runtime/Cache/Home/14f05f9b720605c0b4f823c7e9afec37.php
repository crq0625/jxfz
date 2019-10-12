<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>用户登陆</title>
		<!-- Bootstrap -->
		<link href="/Static/home/css/bootstrap.min.css" rel="stylesheet">
		<link href="/Static/home/css/reg.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
	        <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
		<script src="/Static/home/js/jquery.min.js" type="text/javascript"></script>
		<script src="/Static/home/js/jquery.validate.js" type="text/javascript"></script>
		<style>
			body {
				background-color: #FFF;
			}
		</style>
	</head>

	<body>
		<div class="container">
			<div class="row">
				<form class="reg_from" method="POST" action="<?php echo U('Public/login');?>">
					<h2>会员登陆</h2>
					<div class="form-group">
						<input type="text" class="form-control" id="email" name="email" placeholder="请输入验证邮箱">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="password" name="password" placeholder="请输入密码">
					</div>
					<button type="submit" class="btn btn-lg btn-primary btn-block">登陆</button>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="checkbox"> 记住密码
						</label>
						<label style="float:right;">
							<a href="<?php echo U('Public/reg');?>">前往注册</a>
						</label>
					</div>

				</form>
			</div>

		</div>

	</body>
	<script>
		$(".reg_from").validate({
			rules: {
				email: "required",
				password: {
					required: true,
					minlength: 5
				},
			},
			messages: {
				email: "请输入用户名或邮箱",
				password: {
					required: "请输入密码",
					minlength: jQuery.format("密码不能小于{0}个字 符")
				},
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('error');
				$(element).parents('.form-group').addClass('success');
			}
		});
	</script>

</html>