<?php
/**
 * 
 * @author wxf
 *系统管理：包括管理员管理、角色管理、菜单管理、权限管理
 */
namespace Admin\Controller;
use Admin\Common\Tree;
class SystemController extends AdmController {
	
	
	function _initialize() {
		parent::_initialize();
	}
	
	/**
	 * 系统管理
	 */
	//系统管理首页
	public function index() {
		$this->display();
	}
	
	/**
	 * 管理员管理
	 */
	//管理员列表
	public function admin() {
		$datas = M("Sysadmin")->where(1)->order(id)->select();
		foreach ($datas as $k=>$v){
			if($v['id']==1){
				$datas[$k]['role_name']="";
			}else{
				$info=M("Sysadminrole")->join("zx_sysrole on zx_sysadminrole.role_id=zx_sysrole.id")->where("user_id=".$v['id'])->find();
				$datas[$k]['role_name']=$info['rname'];
			}
		}
		$this->assign("data",$datas);
		$this->display();
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
			if(M("Sysadmin")->where('id ='.$id)->save($save)){
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	
	//添加管理员
	public function admin_add(){
		$this->display();
	}
	
	//添加管理员提交
	public function admin_add_post(){
		if(IS_POST){
			if(!empty($_POST['username'])){
				//$role_id=$_POST['role_id'];
				$data['username']=$_POST['username'];
				$data['nickname']=$_POST['nickname'];
				$data['password']=md5($_POST['password']);
				$data['email']=$_POST['email'];
				$data['addtime']=time();
				$data['status']=1;
				$rows=M("Sysadmin")->add($data);
				if($rows){
					//M("Sysadminrole")->add(array("role_id"=>$role_id,"user_id"=>$rows));
					$this->success("添加成功！", U("System/admin"));
				}else{
					$this->error("添加失败！");
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
		}else{
			$this->error(M("Sysadmin")->getError());
		}
	}
	
	//管理员修改
	public function admin_edit(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$roles=M("Sysrole")->where("status=1")->order("id asc")->select();
		$this->assign("roles",$roles);
		
		$role_ids=M("Sysadminrole")->where(array("user_id"=>$id))->find();
		$this->assign("role_ids",$role_ids);
		
		$data=M("Sysadmin")->find($id);
		$this->assign('data',$data);
		$this->display();
	}
	
	//管理员修改提交
	public function admin_edit_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$rowda=M('Sysadmin')->find($id);//查找原数据里面的email
			$email1=$rowda['email'];
			$email2=$_POST['email'];
			if($email1!=$email2){//判断Email是否已存在
				$datas1=M('Sysadmin')->where("email='".$email2."'")->select();
				if(!empty($datas1)){
					$this->error("该邮箱已存在");
				}
			}
		
			if(!empty($_POST['username'])){
				//$role_id=$_POST['role_id'];
				$data['username']=$_POST['username'];
				$data['nickname']=$_POST['nickname'];
				if($_POST['password']){
					$ndata['password']=md5($_POST['password']);
				}
				$data['email']=$_POST['email'];
				$data['edittime']=time();
				$data['status']=1;
				$rows=M('Sysadmin')->where("id=".$id)->save($data);
				if($rows){
					//M("Sysadminrole")->where("user_id=".$id)->delete();
					//M("Sysadminrole")->add(array("role_id"=>$role_id,"user_id"=>$id));
					$this->success("修改成功！", U("System/admin"));
				}else{
					$this->error("修改失败！");
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
		}
	}
	
	/**
	 * 角色管理
	 */
	//角色列表
	public function role(){
		$data=M("Sysrole")->where(1)->select();
		$this->assign("data",$data);
		$this->display();
	}
	
	//排序角色
	public function sort(){
		if(IS_POST){
			$data=$_POST['sort'];
			foreach ($data as $k=>$v){
				$row['sort']=$v;
				M("Sysrole")->where("id=".$k)->save($row);
			}
			$this->success("排序成功",U("System/role"));
		}
	}
	
	//添加角色
	public function role_add(){
		$this->display();
	}
	
	//添加角色提交
	public function role_add_post(){
		if(IS_POST){
			$data=$_POST;
			$data['addtime']=time();
			$rows=M("Sysrole")->add($data);
			if($rows){
				$this->success("添加成功！",U('System/role'));
			}else{
				$this->error("添加失败！");
			}
		}
	}
	
	//修改角色
	public function role_edit(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$data=M("Sysrole")->where("id=".$id)->find();
		$this->assign("data",$data);
		$this->display();
	}
	
	//修改角色提交
	public function role_edit_post(){
		if(IS_POST){
			$id=!empty($_GET['id']) ? $_GET['id'] : 0;
			$data=$_POST;
			$data['edittime']=time();
			$rows=M("Sysrole")->where("id=".$id)->save($data);
			if($rows){
				$this->success("修改成功",U('System/role'));
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	//删除角色
	public function role_delete(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$rows=M("Sysrole")->where("id=".$id)->delete();
		if($rows){
			$this->success("删除成功！",U('System/role'));
		}else{
			$this->error("删除失败！");
		}
	}
	
	/**
	 * 菜单管理
	 */
	//菜单列表
	public function menu(){
		$result = M('Menu')->order("sort asc")->select();
		$tree = new Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
		$newmenus=array();
		foreach ($result as $m){
			$newmenus[$m['id']]=$m;
		
		}
		foreach ($result as $n=> $r) {
		
			$result[$n]['level'] = $this->_get_level($r['id'], $newmenus);
			$result[$n]['parentid_node'] = ($r['pid']) ? ' class="child-of-node-' . $r['pid'] . '"' : '';
		
			$result[$n]['str_manage'] = '<a href="' . U("Menu/add", array("pid" => $r['id'], "menuid" => $_GET['id'])) . '">添加子菜单</a> | <a href="' . U("Menu/edit", array("id" => $r['id'], "menuid" => $_GET['id'])) . '">修改</a> | <a class="" href="' . U("Menu/delete", array("id" => $r['id'], "menuid" => $_GET['id']) ). '">删除</a> ';
			$result[$n]['status'] = $r['status'] ? "显示" : "隐藏";
		}
		$tree->init($result);
		$str = "<tr id='node-\$id' \$parentid_node>
					<td><input name='sort[\$id]' type='text' size='3' value='\$sort' class='input input-order'></td>
					<td>\$id</td>
        			<td>\$name</td>
					<td>\$spacer\$title</td>
					<td>\$spacer\$navlink</td>
				    <td>\$status</td>
					<td>\$str_manage</td>
				</tr>";
		$categorys = $tree->get_tree(0, $str);
		$this->assign("categorys", $categorys);
		$this->display();
	}
	
	/**
	 * 获取菜单深度
	 * @param $id
	 * @param $array
	 * @param $i
	 */
	protected function _get_level($id, $array = array(), $i = 0) {
	
		if ($array[$id]['parentid']==0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid']==$id){
			return  $i;
		}else{
			$i++;
			return $this->_get_level($array[$id]['parentid'],$array,$i);
		}
	
	}
	
	//菜单添加
	public function menu_add(){
		import("Tree");
		$tree = new Tree();
		$parentid = intval(I("get.parentid"));
		$result = M('Menu')->order("listorder asc")->select();
		foreach ($result as $r) {
			$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
			$array[] = $r;
		}
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$tree->init($array);
		$select_categorys = $tree->get_tree(0, $str);
		$this->assign("select_categorys", $select_categorys);
		$this->display();
	}
	
	//菜单添加提交
	public function menu_add_post(){
		if(IS_POST){
			$data=$_POST;
			$rows=M('Menu')->add($data);
			if($rows){
				//根据菜单数据得到权限数据并插入到表中
				$rdata['id']=$rows;
				$rdata['module']=$data['model'];
				$rdata['type']='admin_url';
				$rdata['name']=$data['app'].'/'.$data['model'].'/'.$data['action'];
				$rdata['title']=$data['name'];
				$rdata['status']=$data['status'];
				$r=M('auth_rule')->add($rdata,array(),true);
				$this->success("添加成功！", U("Menu/index"));
			}else{
				$this->error("添加失败！");
			}
		}
	}
	
	//删除管理员
	public function admin_delete(){
		$id=!empty($_GET['id']) ? $_GET['id'] : 0;
		$rows=M("Sysadmin")->where("id=".$id)->delete();
		if($rows>0){
			$this->redirect('Admin/index');
		}else{
			$this->error("删除失败");
		}
	}
		
}