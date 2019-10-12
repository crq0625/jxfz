<?php
namespace Admin\Controller;
class MemberController extends AdmController {
	protected $member_obj;
	protected $group_obj;
	protected $community_obj;
	protected $comment_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->member_obj=M("Member");
		$this->group_obj = M("Group");
		$this -> community_obj = M("Community");
		$this -> comment_obj = M("Comment");
	}

	//后台会员列表页
	public function index() {
		$lists = $this->pageList($this->member_obj,1000,null,"id desc");
		$data = $lists['list'];
		$page = $lists['page'];
		foreach($data as $k => $v){
			$group = $this -> group_obj -> where("id=".$v['group_id']) -> select();
			$data[$k]['group_name'] = $group[0]['name'];
			$choose = $this -> community_obj -> where("user_id=".$v['id']) -> select();
			$data[$k]['community_num'] = count($choose,0);
			if(!empty($choose)){
				foreach($choose as $k1 => $v1){
					$comment = $this -> comment_obj -> where("community_id = ".$v1['id']) -> count();
					$data[$k]['comment_num'] = $comment;
				}
			}else{
				$data[$k]['comment_num'] = 0;
			}
		}
		$this -> assign("page",$page);
		$this -> assign("data",$data);
		$this -> display();
	}
	
	//根据时间筛选用户
	public function searchDate(){
		$starttime = strtotime($_POST['starttime']);
		$endtime = strtotime($_POST['endtime']);
		$lists = $this -> pageList($this->member_obj,10000,null,"id desc");
		$list = $lists['list'];
		$page = $lists['page'];
		foreach($list as $k => $v){
			$choose = $this -> community_obj -> where("addtime >".$starttime." and addtime < ".$endtime." and user_id=".$v['id']) -> select();
			$list[$k]['community_num'] = count($choose,0);
			if(!empty($choose)){
				foreach($choose as $k1 => $v1){
					$comment = $this -> comment_obj -> where("community_id = ".$v1['id']) -> count();
					$list[$k]['comment_num'] = $comment;
				}
			}else{
				$data[$k]['comment_num'] = 0;
			}
		}
		$this -> assign("starttime",$_POST['starttime']);
		$this -> assign("endtime",$_POST['endtime']);
		$this -> assign("data",$list);
		$this -> assign("page",$page);
		$this -> display("search");
	}
	
	//根据用户名筛选用户
	public function searchUser(){
		$username = $_POST['username'];
		$starttime = strtotime($_POST['starttime']);
		$endtime = strtotime($_POST['endtime']);
		$lists = $this -> pageList($this->member_obj,10000,"username like '%".$username."%'","id desc");
		$list = $lists['list'];
		$page = $lists['page'];
		foreach($list as $k => $v){
			$choose = $this -> community_obj -> where("addtime >".$starttime." and addtime < ".$endtime." and user_id=".$v['id']) -> select();
			$list[$k]['community_num'] = count($choose,0);
			if(!empty($choose)){
				foreach($choose as $k1 => $v1){
					$comment = $this -> comment_obj -> where("community_id = ".$v1['id']) -> count();
					$list[$k]['comment_num'] = $comment;
				}
			}else{
				$data[$k]['comment_num'] = 0;
			}
		}
		$this -> assign("username",$username);
		$this -> assign("starttime",$_POST['starttime']);
		$this -> assign("endtime",$_POST['endtime']);
		$this -> assign("data",$list);
		$this -> assign("page",$page);
		$this -> display("search");
	}
	
	//后台审核会员列表
	public function check(){
		$data=$this->member_obj->where("status=0")->select();
		$this->assign("data",$data);
		$this->display();
	}
	
	//后台用户添加
	public function add(){
		$grouplist = $this -> group_obj -> select();
		$this -> assign('grouplist',$grouplist);
		$this->display();
	}
	
	//添加管理员提交
	public function add_post(){
		if(IS_POST){
			if(!empty($_POST['username'])){
				$data['username']=$_POST['username'];
				$data['password']=md5($_POST['password']);
				$data['email']=$_POST['email'];
				$data['nickname']=$_POST['nickname'];
				$data['truename']=$_POST['truename'];
				$data['carid']=$_POST['carid'];
				$data['sex']=$_POST['sex'];
				$data['type']=$_POST['type'];
				$data['group_id'] = $_POST['group_id'];
				$data['addtime']=time();
				$data['regip']=get_client_ip();
				$data['status']=1;
				$rows=$this->member_obj->add($data);
				if($rows>0){
					$this->success("添加成功！", U("Member/index"));
				}else{
					$this->error("添加失败！");
				}
			}else{
				$this->error($this->member_obj->getError());
			}
		}
	}
	
//后台用户修改
	public function edit(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		
		$data=$this->member_obj->find($id);
		
		$grouplist = $this -> group_obj -> select();
		$this -> assign('grouplist',$grouplist);
		$this->assign('data',$data);
		$this->display();
	}
	
	//修改提交
	public function edit_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$rowda=$this->member_obj->find($id);//查找原数据里面的email
			$email1=$rowda['email'];
			$email2=$_POST['email'];
			if($email1!=$email2){//判断Email是否已存在
				$datas1=$this->member_obj->where("email='".$email2."'")->select();
				if(!empty($datas1)){
					$this->error("该邮箱已存在");
				}
			}
			$ndata['username']=$_POST['username'];
			if($_POST['password']){
				$ndata['password']=md5($_POST['password']);
			}
			$ndata['email']=$_POST['email'];
			$ndata['nickname']=$_POST['nickname'];
			$ndata['truename']=$_POST['truename'];
			$ndata['sex']=$_POST['sex'];
			$ndata['type']=$_POST['type'];
			$ndata['edittime']=time();
			$ndata['group_id'] = $_POST['group_id'];
			$rows=$this->member_obj->where("id=".$id)->save($ndata);
			if($rows>0){
				$this->success("修改成功！", U("Member/index"));
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	//修改管理员状态
	public function updatestatus(){
		if(IS_POST){
			$id = $_POST['id'];
			$status = $_POST['status'];
			if($status==1){
				$save['status'] = 0;
			}else{
				$save['status'] = 1;
			}
			if($this->member_obj->where('id ='.$id)->save($save)){
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	
	//AJAX验证用户名是否存在
	public function checkuser(){
		if(IS_AJAX){
			if(!empty($_POST['username'])){
				$datas1=$this->member_obj->where("username='".$_POST['username']."'")->select();
				if(empty($datas1)){
					echo 'true';//数据库不存在当前用户名，即可用
				}else{
					echo 'false';//当前用户名不可用
				}
			}
		}
	}
	
	//AJAX验证邮箱是否存在
	public function checkemail(){
		if(IS_AJAX){
			if(!empty($_POST['email'])){
				$datas1=$this->member_obj->where("email='".$_POST['email']."'")->select();
				if(empty($datas1)){
					echo 'true';
				}else{
					echo 'false';
				}
			}
		}
	}
	
	//后台用户删除
	public function delete(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$rows=$this->member_obj->where("id=".$id)->delete();
		if($rows>0){
			$this->redirect('Member/index');
		}else{
			$this->error("删除失败");
		}
	}

}