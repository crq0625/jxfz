<?php

namespace Admin\Controller;
class MainController extends AdmController {

	

	public function index() {

		//服务器信息
		$info = array(
			'操作系统'		 => PHP_OS,
			'运行环境'		 => $_SERVER["SERVER_SOFTWARE"],
			'PHP运行方式'	 => php_sapi_name(),
			'MYSQL版本'	 => mysql_get_server_info(),
			'程序版本'		 => CMF_VERSION,
			'上传附件限制'	 => ini_get('upload_max_filesize'),
			'执行时间限制'	 => ini_get('max_execution_time') . "秒",
			'剩余空间'		 => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
		);
//		$sms = json_decode(file_get_contents('http://www.thinkcmf.com/service/sms.php'), true);
		$sms = array(
			1	 => array('id' => '1', 'title' => '系统发布', 'content' => 'MenggeCMS 欢迎体验',),
			2	 => array('id' => '2', 'title' => '构架编码', 'content' => '<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=759498475&site=qq&menu=yes">jack</a>',),
			3	 => array('id' => '3', 'title' => 'QQ讨论', 'content' => 'MenggeCMS官方QQ群 群号是219059680',),
		);
		$this->assign('server_info', $info);
		$this->assign('sms', $sms);
		$this->display();
	}

}
