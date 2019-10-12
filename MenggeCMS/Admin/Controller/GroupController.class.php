<?php
namespace Admin\Controller;
class GroupController extends AdmController {
	protected $group_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->group_obj=M("group");
	}

	//后台群组列表页
	public function index() {
		$data=$this->group_obj->select();
		$this->assign("data",$data);
		$this->display();
	}
	
	
	//后台用户添加
	public function add(){
		
		
		$this->display();
	}
	
	//添加群组
	public function add_post(){
		if(IS_POST){
			if(!empty($_POST['name'])){
				$data['name']=$_POST['name'];
				$data['group_intro']=$_POST['group_intro'];
				$info = $this->Pupload();
				$data['group_logo']='./Upload/Admin/pic/'.$info['group_logo']['savepath'].$info['group_logo']['savename'];
				dump($data);exit;
				$data['time']=time();
				$rows=$this->group_obj->add($data);
				
				if($rows>0){
					$this->success("添加成功！", U("Group/index"));
				}else{
					$this->error("添加失败！");
				}
			}else{
				$this->error($this->group_obj->getError());
			}
		}
	}
	
	//后台用户修改
	public function edit(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		
		$data=$this->group_obj->find($id);
		$this->assign('data',$data);
		$this->display();
	}
	
	//修改提交
	public function edit_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$rowda=$this->group_obj->find($id);
			$ndata['name']=$_POST['name'];
			$ndata['group_intro']=$_POST['group_intro'];
			if(!empty($_POST['group_logo'])){
				$info = $this->Pupload();
				$ndata['group_logo']='/Upload/Admin/pic/'.$info['group_logo']['savepath'].$info['group_logo']['savename'];
			}
			$rows=$this->group_obj->where("id=".$id)->save($ndata);
			if($rows>0){
				$this->success("修改成功！", U("Group/index"));
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	//AJAX验证用户名是否存在
	public function checkuser(){
		if(IS_AJAX){
			if(!empty($_POST['username'])){
				$datas1=$this->group_obj->where("name='".$_POST['username']."'")->select();
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
		$rows=$this->group_obj->where("id=".$id)->delete();
		if($rows>0){
			$this->redirect('Group/index');
		}else{
			$this->error("删除失败");
		}
	}
	
}