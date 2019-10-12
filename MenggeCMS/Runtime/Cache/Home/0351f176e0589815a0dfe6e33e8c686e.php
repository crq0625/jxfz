<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

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
		<h1 class="days-title"><?php echo ($system_name); ?>朋友圈</h1>
		<div class="row">
			<div class="col-md-8 days-main">
				<div class="row">
					<div class="col-xs-8">
						<h3><?php echo (date('Y年m月d日',$now)); ?></h3>
					</div>
					<div class="col-xs-4" style="text-align:right;padding-top:18px;">
					</div>
				</div>

				<?php if(is_array($comm)): foreach($comm as $key=>$c): ?><div class="days-post" style="border-bottom: 3px solid #525252;padding-top:20px;padding-bottom: 0px;">
						<table class="table table-bordered">
							<div style="color:red;font-weight: bold;font-size: 16px;">作者：<?php echo ($c['username']); ?><span style="float:right;">
								
								<!-- JiaThis Button BEGIN -->
								<div id="ckepop">
<span class="jiathis_txt">分享到：</span>
								<a class="jiathis_button_weixin">微信</a>
							</div>
<!--							<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1" charset="utf-8"></script>-->
					</div>
					<!-- JiaThis Button END -->

					</span>
			</div>
			<!--判断是否输出时间具象开始-->
			<?php if($c['publish'] == 1): ?><caption>案例主题：<?php echo ($c['content'][0][0]['subject']); ?>
					<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目标：<?php echo ($c['content'][0][0]['target']); ?></span><span style="float:right;">发布时间：<?php echo (date('Y-m-d H:i:s',$c['addtime'])); ?></span></caption>
				<thead>
					<tr>
						<th>时间</th>
						<th>行动（做、做到）</th>
						<th>效果</th>
						<th>启发</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($c['content'][1])): foreach($c['content'][1] as $key=>$rs): ?><tr>
							<td>
								<?php echo ($rs["starttime"]); ?>
								<br>～
								<br><?php echo ($rs["endtime"]); ?>
							</td>
							<td><?php echo ($rs["actions"]); ?></td>
							<td><?php echo ($rs["results"]); ?></td>
							<td><?php echo ($rs["inspire"]); ?></td>
						</tr><?php endforeach; endif; ?>
				</tbody><?php endif; ?>
			<!--判断是否输出时间具象结束-->
			<!--判断是否输出事件具象开始-->
			<?php if($c['publish'] == 2): ?><caption>案例主题：<?php echo ($c['content'][0][0]['subject']); ?>
					<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目标：<?php echo ($c['content'][0][0]['target']); ?></span><span style="float:right;">发布时间：<?php echo (date('Y-m-d H:i:s',$c['addtime'])); ?></span></caption>
				<thead>
					<tr>
						<th>事件</th>
						<th>行动（做、做到）</th>
						<th>效果</th>
						<th>启发</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($c['content'][1])): foreach($c['content'][1] as $key=>$rs): ?><tr>
							<td>
								<?php echo ($rs["event"]); ?>
							</td>
							<td><?php echo ($rs["actions"]); ?></td>
							<td><?php echo ($rs["results"]); ?></td>
							<td><?php echo ($rs["inspire"]); ?></td>

						</tr><?php endforeach; endif; ?>
				</tbody><?php endif; ?>
			<!--判断是否输出事件具象开始-->
			<!--判断是否输出观察者具象开始-->
			<?php if($c['publish'] == 3): ?><caption>案例主题：<?php echo ($c['content'][0][0]['subject']); ?>
					<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目标：<?php echo ($c['content'][0][0]['target']); ?></span><span style="float:right;">发布时间：<?php echo (date('Y-m-d H:i:s',$c['addtime'])); ?></span></caption>
				<thead>
					<tr>
						<th colspan="2">被具象人</th>
						<th colspan="2">具象支持人</th>
					</tr>
					<tr>
						<th>行动、事实</th>
						<th>思想</th>
						<th>行动、事实</th>
						<th>思想</th>
					</tr>

				</thead>
				<tbody>
					<?php if(is_array($c['content'][1])): foreach($c['content'][1] as $key=>$rs): ?><tr>
							<th>
								<?php echo ($rs["observe_actions"]); ?>
							</th>
							<th>
								<?php echo ($rs["observe_think"]); ?>
							</th>
							<th>
								<?php echo ($rs["observe_actions1"]); ?>
							</th>
							<th>
								<?php echo ($rs["observe_think1"]); ?>
							</th>
						</tr><?php endforeach; endif; ?>
					<tr>
						<!--<th>
											总结：

										</th>
										<th colspan="3">
											<?php echo ($res["summ"]); ?>
										</th>-->

					</tr>
				</tbody><?php endif; ?>
			<!--判断是否输出观察者具象结束-->
			<tfoot>
				<tr>
					<th>评论：</th>
					<th colspan="3">
						<?php if(is_array($c['comment'])): foreach($c['comment'] as $key=>$rc): ?><p>
								<?php if($rc['usertype'] == 1): ?><a style="color:red;"><?php echo ($rc["commuser"]); ?></a>
									<?php else: ?> <?php echo ($rc["commuser"]); endif; ?> :<?php echo ($rc["content"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="replyuser" data-toggle="modal" data-target=".bs-example-modal-lg" data_id="<?php echo ($rc["id"]); ?>">回复</a>
								
							</p>
							
								<?php if(is_array($rc['reply'])): foreach($rc['reply'] as $key=>$rcs): ?><p>
									<?php if($rcs['usertype'] == 1): ?><a style="color:red;"><?php echo ($rcs["replyuser"]); ?></a>
									<?php else: ?> <?php echo ($rcs["replyuser"]); endif; ?> &nbsp;&nbsp;<span>回复</span>&nbsp;&nbsp;<?php echo ($rcs["replyusername"]); ?>:<?php echo ($rcs["content"]); ?>
									
									</p><?php endforeach; endif; endforeach; endif; ?>

					</th>
				</tr>
			</tfoot>

			</table>
			<div class="comment">
				<form method="post" action="<?php echo U('Community/addComment');?>">
					<input placeholder="我来说一句" class="table table-bordered" type="text" name="content" style="height:40px;" />
					<input type="hidden" value="<?php echo ($c['id']); ?>" name="community_id" />
					<!--<textarea class="form-control" rows="3" name="comment"></textarea>-->
					<input class="btn btn-default" style="float:right;margin-top:-15px;" type="submit" value="评论">
				</form>

			</div>
			
			<div class="reply" style="display:none;">
			<form method="post" action="<?php echo U('Community/reply');?>">
				<input placeholder="回复" class="table table-bordered" type="text" name="content" style="height:40px;" />
				<input type="hidden" name="pid" class="reply_id" />
				<input type="hidden" name="community_id" value="<?php echo ($c['id']); ?>" class="community_id" />
				<input class="btn btn-default" style="float:right;margin-top:-15px;" type="submit" value="回复">
			</form>
	
			
		</div>

			<p>总结：<?php echo ($c['content'][0][0]['summary']); ?></p>
		</div><?php endforeach; endif; ?>
			<script>
			$(".replyuser").click(function(){
				$(".comment").css('display','none');
				$(".reply").css('display','block');
				$(this).css('display','none');
				var dataid = $(this).attr('data_id');
				$(".reply_id").val(dataid);
			});
			
			
		</script>
		<!--回复评论开始-->
		<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">回复</button>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">回复评论</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="message-text" class="control-label">评论内容:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary">回复</button>
      </div>
    </div>
  </div>
</div>
<script>
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('.modal-title').text('New message to ' + recipient)
  modal.find('.modal-body input').val(recipient)
})
</script>-->
		<!--回复评论结束-->

		<hr>
		<div class="pages" style="margin:0 auto;text-align: center;"><?php echo ($page); ?></div>
	</div>
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
						window.location.href = "<?php echo U('Community/eveChoose');?>" + '&newDate=' + new_url;
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