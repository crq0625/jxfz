<?php
namespace Admin\Controller;
class WorkController extends AdmController {
	protected $work_obj;
	protected $group_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->work_obj=M("work");
		$this->group_obj=M("group");
	}

	//后台列表页
	public function index() {
		$data=$this->work_obj->select();
		$this->assign("data",$data);
		$this->display();
	}
	
	
	//后台添加
	public function add(){
		$data = $this->group_obj->select();
		$this->assign('data',$data);
		$this->display();
	}
	
	//添加提交
	public function add_post(){
		if(IS_POST){
			if(!empty($_POST['subject'])){
				$data['subject']=$_POST['subject'];
				$data['target']=$_POST['target'];
				$data['summary']=$_POST['summary'];
				$data['group_id']=$_POST['group_id'];
				$data['user_id']=$_SESSION['admin']['id'];
				$data['time']=time();
				$rows=$this->work_obj->add($data);
				if($rows>0){
					$this->success("添加成功！", U("Work/index"));
				}else{
					$this->error("添加失败！");
				}
			}else{
				$this->error($this->work_obj->getError());
			}
		}
	}
	
	//后台修改
	public function edit(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		
		$data=$this->work_obj->find($id);
		$this->assign('data',$data);
		
		$gdata = $this->group_obj->select();
		$this->assign('gdata',$gdata);
		$this->display();
	}
	
	//修改提交
	public function edit_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$rowda=$this->work_obj->find($id);
			
			$ndata['subject']=$_POST['subject'];
			$ndata['target']=$_POST['target'];
			$ndata['summary']=$_POST['summary'];
			$ndata['group_id']=$_POST['group_id'];
			$ndata['user_id']=$_SESSION['admin']['id'];
			$ndata['time']=time();
			
			$rows=$this->work_obj->where("id=".$id)->save($ndata);
			if($rows>0){
				$this->success("修改成功！", U("Work/index"));
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	//AJAX验证用户名是否存在
	public function checkuser(){
		if(IS_AJAX){
			if(!empty($_POST['username'])){
				$datas1=$this->work_obj->where("subject='".$_POST['username']."'")->select();
				if(empty($datas1)){
					echo 'true';//数据库不存在当前用户名，即可用
				}else{
					echo 'false';//当前用户名不可用
				}
			}
		}
	}
	
	//后台用户删除
	public function delete(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$rows=$this->work_obj->where("id=".$id)->delete();
		if($rows>0){
			$this->redirect('Work/index');
		}else{
			$this->error("删除失败");
		}
	}
}