<?php
namespace Admin\Controller;
class PublicController extends AdmController {
	
	public function _initialize(){
		
	}
	
	public function login(){
		
		$this->display();
	}
	
	public function dologin() {
		$name = $_POST['username'];
		$pass = $_POST['password'];
		$verify = $_POST['verify'];
		//验证码
		if (!check_verify($verify)) {
			$this->error("验证码错误！");
		}
		$user = D("Sysadmin");
		$result = $user->where("username = '$name'")->find();
		if ($result != null) {
			if($result['status'] == 0){
				$this->error("该用户已经被停用！");
			}else{
				if ($result['password'] == md5($pass)) {
					//登入成功页面跳转
					if($result['id']==1){
						session("adm",1);
					}
					session("authId", $result['id']);
					$admin['id']=$result['id'];
					$admin['member']=$result['username'];
					session("admin", $admin);
					$result['lastlogin'] = get_client_ip();
					$result['lastlogintime'] = time();
					$user->save($result);
					$this->redirect('Index/index');
					$this->success("登录验证成功！", U("Index/index"));
				} else {
					$this->error("密码错误！");
				}
			}
		} else {
			$this->error("用户名不存在！");
		}
	}
	public function logout() {
		unset($_SESSION['admin']);
		unset($_SESSION['adm']);
		unset($_SESSION['authId']);
		$this->redirect("public/login");
	}
}