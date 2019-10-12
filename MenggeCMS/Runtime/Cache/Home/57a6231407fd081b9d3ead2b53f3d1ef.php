<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>

<html lang="zh-CN">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

		<title><?php echo ($system_name); ?>&系统</title>
		<meta name="description" content="">
		<meta name="author" content="镜一">

		<link href="/Static/home/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="/Static/home/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
		<link href="/Static/home/css/page.css" rel="stylesheet" >
		<link href="/Static/home/css/bootstrap-clockpicker.min.css" rel="stylesheet" media="screen">
		<link href="/Static/home/css/bootstrap-switch.min.css" rel="stylesheet" media="screen">

		<style type="text/css">
			textarea {
				resize: none;
			}
		</style>

		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="/Static/home/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="/Static/home/js/ie-emulation-modes-warning.js"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
      <script src="/Static/home/js/html5shiv.min.js"></script>
      <script src="/Static/home/js/respond.min.js"></script>
    <![endif]-->
		<script src="/Static/home/js/jquery.min.js"></script>
		<script src="/Static/home/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<style>
			/* Move down content because we have a fixed navbar that is 50px tall */
			
			body {
				padding-top: 50px;
				padding-bottom: 20px;
			}
			.jumbotron {
				background-image: url(/Static/home/images/bg.png);
				background-position: center;
			}
		</style>
		<script src="/Static/home/js/ie10-viewport-bug-workaround.js" charset="UTF-8"></script>
		<script type="text/javascript" src="/Static/home/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<script type="text/javascript" src="/Static/home/js/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
		<script type="text/javascript" src="/Static/home/js/jquery-clockpicker.min.js" charset="UTF-8"></script>
		<script type="text/javascript" src="/Static/home/js/bootstrap-switch.min.js" charset="UTF-8"></script>
		<script>
			var _hmt = _hmt || [];
			(function() {
				var hm = document.createElement("script");
				hm.src = "//hm.baidu.com/hm.js?4e365af26d5b6519ebbac39e800c4b5f";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(hm, s);
			})();
		</script>

	</head>

	<body>

		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><?php echo ($system_name); ?></a>

				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<?php if(!empty($_SESSION['username'])): ?><li <?php if($controller == Work): ?>class = "active"<?php endif; ?> ><a href="<?php echo U('Work/index');?>">每日<?php echo ($system_name); ?></a></li>
						<li <?php if($controller == Community): ?>class = "active"<?php endif; ?>><a href="<?php echo U('Community/index');?>"><?php echo ($system_name); ?>群组</a></li><?php endif; ?>
						<!--<li><a href="">事件具象</a></li>
						<li><a href="">人物具象</a></li>
						<li><a href="">观察者具象</a></li>
						<li><a href="">目标达成</a></li>
						<li><a href="">具象心得</a></li>-->
					</ul>
					<?php if(empty($_COOKIE['username']) and empty($_SESSION['username'])): ?><div class="navbar-form navbar-right">
							<a type="button" href="<?php echo U('public/userlogin');?>" class="btn btn-success">登陆</a>
							<a type="button" href="<?php echo U('public/reg');?>" class="btn btn-success">注册</a>
						</div><?php endif; ?>

					<?php if(!empty($_COOKIE['username']) or !empty($_SESSION['username'])): ?><div class="navbar-form navbar-right">
							<font style="color:white;">你好，<?php echo ($_SESSION['username']); ?></font>&nbsp;&nbsp;&nbsp;
							<a href="<?php echo U('public/logout');?>" class="btn btn-success">注销</a>
						</div><?php endif; ?>

				</div>
				<!--/.navbar-collapse -->
			</div>
		</nav>
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="container">
	<div class="days-header">
		<h1 class="days-title">每日<?php echo ($system_name); ?></h1>
		<p class="lead days-description"><?php echo ($system_name); ?>时间练习作业</p>
		<div class="row">
			<div class="col-md-8 days-main">
				<div class="days-post">
					<div class="row">
						<div class="col-xs-8">
							<h3><?php echo (date('Y年m月d日',$addtime)); ?></h3>
						</div>
						<div class="col-xs-4" style="text-align:right;padding-top:18px;">
							<input type="checkbox" id="my-checkbox" data-on-text="浏览" data-off-text="编辑" data-off-color="success">
							<script>
								 //编辑开关
								$("#my-checkbox").bootstrapSwitch();
								$('#my-checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
									if (state) window.location.href = "<?php echo U('Work/worklist');?>" + '&chooseDate=<?php echo ($addtime); ?>';
									else window.location.href = "<?php echo U('Work/edit');?>" + '&chooseDate=<?php echo ($addtime); ?>';
								});
							</script>
						</div>
					</div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="<?php echo U('Work/edit');?>">时间<?php echo ($system_name); ?></a></li>
						<li><a href="<?php echo U('Work/eventList');?>">事件<?php echo ($system_name); ?></a></li>
						<li><a href="<?php echo U('Work/observeList');?>">观察者<?php echo ($system_name); ?></a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<!--时间具象开始-->
						<div class="tab-pane fade in active">
							<table class="table table-bordered">
								<caption style="margin:0;padding:0px;padding-top:5px;">案例主体：<?php echo ($subject); ?>
									<p style="">目标：<?php echo ($target); ?>
										<p>
								</caption>
								<a class="btn btn-default" href="<?php echo U('Work/publishTime'," workId=".$workId);?>" role="button" style="float:right;margin-top:10px;">发布</a>
								<thead>
									<tr>
										<th style="width:78px;">时间</th>
										<th>行动（做、做到）</th>
										<th>效果</th>
										<th>启发</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($result)): if(is_array($result)): foreach($result as $key=>$r): if($r['status'] == 1): ?><form method="post" action="<?php echo U('Work/editActCont','id='.$r[id]);?>">
													<input type="hidden" name="workRateId" value="<?php echo ($r["id"]); ?>" />
													<input type="hidden" name="chooseDate" value="<?php echo ($addtime); ?>" /><?php endif; ?>

											<tr>
												<?php if($r['status'] == 1): ?><td>
														<div class="clockpicker">
															<input name="starttime" type="text" placeholder="开始" value="<?php echo ($r['starttime']); ?>" class="form-control" >
														</div>
														<div class="clockpicker">
															<input name="endtime" style="margin-top:20px;" type="text" placeholder="结束" value="<?php echo ($r['endtime']); ?>" class="form-control" >
														</div>
													</td>
													<?php else: ?>
													<td style="text-align:center;">

														<?php echo ($r["starttime"]); ?>
														<br>～
														<br><?php echo ($r['endtime']); ?>
													</td><?php endif; ?>
												<td>
													<?php if($r['status'] == 1): ?><textarea class="form-control" rows="3" name="done"><?php echo ($r["actions"]); ?></textarea>
														<?php else: ?> <?php echo ($r["actions"]); endif; ?>
												</td>
												<td>
													<?php if($r['status'] == 1): ?><textarea class="form-control" rows="3" name="effect"><?php echo ($r["results"]); ?></textarea>
														<?php else: ?> <?php echo ($r["results"]); endif; ?>
												</td>
												<td>
													<?php if($r['status'] == 1): ?><textarea class="form-control" rows="3" name="think"><?php echo ($r["inspire"]); ?></textarea>
														<?php else: ?> <?php echo ($r["inspire"]); endif; ?>
												</td>
												<td>
													<?php if($r['status'] == 1): ?><input class="btn btn-success btn-sm" style="height:70px;" type="submit" value="保存">
														<?php else: ?>
														<a class="btn btn-info btn-sm" href="<?php echo U('Work/editAction','id='.$r['id']);?>&chooseDate=<?php echo ($addtime); ?>">编辑</a>
														<br><a class="btn btn-danger btn-sm" style="margin-top:2px;" href="<?php echo U('Work/editDel','id='.$r['id']);?>" onclick="return confirm('确定要删除吗?');">删除</a><?php endif; ?>
												</td>
											</tr>
											<?php if($r['status'] == 1): ?></form><?php endif; endforeach; endif; endif; ?>
									<form method="post" action="<?php echo U('Work/edit');?>">
										<input name="addChooseDate" type="hidden" value="<?php echo ($addtime); ?>" />
										<tr>
											<td>
												<div class="clockpicker">
												<input name="starttime" size="5" type="text" value="" placeholder="开始" class="form-control" readonly />
												</div>
												<div class="clockpicker">
												<input name="endtime" style="margin-top:20px;" size="5" type="text" placeholder="结束" class="form-control" readonly />
												</div>
											</td>
											<td>
												<textarea class="form-control" rows="3" name="done"></textarea>
											</td>
											<td>
												<textarea class="form-control" rows="3" name="effect"></textarea>
											</td>
											<td>
												<textarea class="form-control" rows="3" name="think"></textarea>
											</td>
											<td>
												<input class="btn btn-success btn-sm" style="height:90px;" type="submit" value="创建">
											</td>
										</tr>
									</form>
								</tbody>
							</table>
						</div>
						<!--时间具象结束-->

					</div>

					<form action="<?php echo U('Work/addsum');?>" method="post">
						案例主体:
						<input type="text" name="who" class="form-control" value="<?php echo ($subject); ?>"> 目标:
						<input type="text" name="goal" class="form-control" value="<?php echo ($target); ?>">
						<h4>总结</h4>
						<p>
							<textarea name="summary" class="form-control"><?php echo ($summary); ?></textarea>
						</p>
						<input type="hidden" value="<?php echo ($workId); ?>" name="workId" />
						<input type="submit" class="btn btn-success" value="提交">
					</form>

				</div>
				<hr>
			</div>
			<script>
				  //时钟
   					 $('.clockpicker').clockpicker();
				 //				 $(".clockpicker input ").datetimepicker({format: 'hh:ii'});
			</script>
			<div style="display:none;">
			<div class="col-md-3 col-md-offset-1 days-sidebar">
				<div class="sidebar-module sidebar-module-inset">
					<h3>日期选择</h3>
					<div class="form-group">
						<div class="input-group date form_date " id="godate" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" size="16" type="text" value="" readonly>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
						<input type="hidden" id="dtp_input2" value="" />
						<br/>
					</div>
					<script type="text/javascript">
						 //日期选择
						$('.form_date').datetimepicker({
							language: 'zh-CN',
							weekStart: 1,
							todayBtn: 1,
							autoclose: 1,
							todayHighlight: 1,
							startView: 2,
							minView: 2,
							forceParse: 0
						}).on('changeDate', function() {
							new_url = document.getElementById('dtp_input2').value;
							//      new_url = new_url.replace(/-/g,"");
							//      new_url = new_url.substr(2);
							window.location.href = "<?php echo U('Work/dateChoose');?>" + '&newDate=' + new_url;
						});
					</script>
				</div>
				
					<div class="sidebar-module sidebar-module-inset">
						<h3>效果</h3>
						<p>1.看事实，行动优先语言；
							<br>2.看目标，没做到约等于没效果。
							<br>3.基于目标，效果不充分可以用公众常识和逻辑做调整行动的依据。
						</p>
					</div>
					<div class="sidebar-module">
						<h3>存档</h3>
						<ol class="list-unstyled">
							<li><a href="/month/201506">2015年6月</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
	
      <hr>
			<footer>
				<p> 版权所有 &copy;2018-2020 北京方兴未艾科技有限公司</p>
			</footer>
		</div>
		<!-- /container -->

	</body>

</html>