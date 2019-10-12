<?php
/**
 *
 * @author Administrator
 * 后台公共文件
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AdmController extends AdminbaseController {
	
	
	function _initialize() {
		parent::_initialize();
	}
	
	/**
	 * 管理员管理
	 */
	//ajax验证管理员用户名
	public function checkadmin(){
		if(IS_AJAX){
			if(!empty($_POST['username'])){
				$datas1=M("Sysadmin")->where("username='".$_POST['username']."'")->select();
				//var_dump($datas1);exit;
				if(empty($datas1)){
					echo 'true';//数据库不存在当前用户名，即可用
				}else{
					echo 'false';//当前用户名不可用
				}
			}
		}
	}
	
	//AJAX验证邮箱是否存在
	public function checkadmin_email(){
		if(IS_AJAX){
			if(!empty($_POST['email'])){
				$datas1=M("Sysadmin")->where("email='".$_POST['email']."'")->select();
				if(empty($datas1)){
					echo 'true';
				}else{
					echo 'false';
				}
			}
		}
	}
}