<?php
namespace Admin\Controller;
class IndexController extends AdmController {
	
	
	function _initialize() {
		parent::_initialize();
	}

	//后台框架首页
	public function index() {
		$this->display();
	}
	
	//个人资料
	public function userinfo(){
		$id=I('id');
		if($id){
			$data=M("Sysadmin")->find($id);
		}
		$this->assign('data',$data);
		$this->display();
	}
	
	//个人资料修改提交
	public function userinfo_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$rowda=M("Sysadmin")->find($id);//查找原数据里面的email
			$email1=$rowda['email'];
			$email2=$_POST['email'];
			if($email1!=$email2){//判断Email是否已存在
				$datas1=M("Sysadmin")->where("email='".$email2."'")->select();
				if(!empty($datas1)){
					$this->error("该邮箱已存在");
				}
			}
			$ndata=$_POST;
			$ndata['edittime']=time();
			$rows=M("Sysadmin")->where("id=".$id)->save($ndata);
			if($rows){
				$this->success("修改成功！", U("Main/index"));
			}else{
				$this->error("修改失败！");
			}
				
		}
	}
	
	//修改密码
	public function setpw(){
		$id=$_SESSION['admin']['id'];
		$this->assign('id',$id);
		$this->display();
	}
	
	//修改密码提交
	public function setpw_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$data['password']=md5($_POST['npassword']);
			$data['edittime']=time();
			$rows=M("Sysadmin")->where("id=".$id)->save($data);
			if($rows){
				$this->success("修改成功！", U("Main/index"));
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	//AJAX验证修改密码时输入的密码是否正确
	public function checkpw(){
		$id=$_SESSION['admin']['id'];
		if(IS_AJAX){
			$datas=M("Sysadmin")->find($id);
			$pw1=$datas['password'];
			if(!empty($_POST)){
				$pw2=md5($_POST['password']);
			}
			if($pw1==$pw2){
				echo 'true';//输入的密码与数据库相匹配，即正确
			}else{
				echo 'false';//当前输入的密码错误
			}
		}
	}
}