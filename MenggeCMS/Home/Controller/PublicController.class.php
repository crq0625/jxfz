<?php
namespace Home\Controller;
use Common\Controller\JxfzbaseController;

class PublicController extends JxfzbaseController {
	protected $member_obj;

	function _initialize() {
		parent::_initialize();
		$this -> member_obj = M("Member");
		$this -> assign('controller', CONTROLLER_NAME);
		$this -> assign('system_name',C('SYSTEM_NAME'));
	}
	
	//用户登录
	public function login() {
		$email = $_POST['email'];
		$pass = $_POST['password'];
		$user = D("member");
		$work = D('Work');
		$result = $user -> where("email = '$email'") -> find();
		if ($result != null) {
			if ($result['password'] == md5($pass)) {
				//登入成功页面跳转
				$admin['id'] = $result['id'];
				$admin['member'] = $result['username'];
				if (!empty($_POST['checkbox'])) {
					session("userId", $result['id']);
					session("username", $result['username']);
					cookie('userId', $result['id'], 60 * 60 * 24 * 30);
					cookie('username', $result['username'], 60 * 60 * 24 * 30);
				} else {
					session("userId", $result['id']);
					session("username", $result['username']);
				}
				$result['regip'] = get_client_ip();
				$result['lastlogintime'] = time();
				$user -> save($result);
				//获取某天时间起点和终点
				$time = time();
				$day_start = $this -> getTimeStart($time);
				$day_end = $this -> getTimeEnd($time);
				$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id=".$result['id']) -> select();
				if (empty($row)) {
					$data['user_id'] = $result['id'];
					$data['target'] = null;
					$data['subject'] = null;
					$data['summary'] = null;
					$data['time'] = time();
					$work -> add($data);
				}
				if (empty($result['group_id'])) {
					$this -> success("登录验证成功！", U('Community/commChoose'));
				} else {
					$this -> success("登录验证成功！", U('Work/index'));
				}
			} else {
				$this -> error("密码错误！");
			}
		} else {
			$this -> error("用户名不存在！");
		}

	}

	//用户注册
	public function reg() {
		if ($_POST) {
			$data['username'] = $_POST['InputUsername'];
			$data['email'] = $_POST['InputEmail'];
			$pwd = $_POST['Inputpassword'];
			$pwd1 = $_POST['Inputpassword1'];
			$user = D("member");
			$work = D('Work');
			if (!empty($_POST['InputEmail'])) {
				$datas1 = $user -> where("email='" . $_POST['InputEmail'] . "'") -> select();
				if (!empty($datas1)) {
					$this -> error('该邮箱已经被注册！请重新输入');
				} else {
					if ($pwd == $pwd1) {
						$data['password'] = md5($pwd);
						$data['addtime'] = time();
						$data['regip'] = get_client_ip();
						$rows = $user -> add($data);
						if ($rows) {
							$userId = $user->getLastInsID();
							$datas['user_id'] = $userId;
							$datas['target'] = null;
							$datas['subject'] = null;
							$datas['summary'] = null;
							$datas['time'] = time();
							$work -> add($datas);
							session("userId", $userId);
							session("username", $data['username']);
							$this -> success("注册成功！", U("Community/commChoose"));
							exit ;
						}
					} else {
						$this -> error("两次输入密码不一致！请重新输入");
					}

				}
			}

		}
		$this -> display('reg');
	}

	//AJAX验证邮箱是否存在
	public function checkemail() {
		$member = D('member');
		if (IS_AJAX) {
			if (!empty($_POST['email'])) {
				$datas1 = $member -> member_obj -> where("email='" . $_POST['email'] . "'") -> select();
				if (empty($datas1)) {
					echo 'true';
				} else {
					echo 'false';
				}
			}
		}
	}

	//用户退出
	public function logout() {
		unset($_SESSION['username']);
		unset($_SESSION['userId']);
		cookie('username', null);
		cookie('userId', null);
		$this -> redirect("index/index");
	}

	//用户登录
	public function userlogin() {
		$this -> display('login');
	}

}
