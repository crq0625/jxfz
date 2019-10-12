<?php
/**
 *
 * @author Administrator
 * 后台基类文件，所有后台公共操作方法写到此文件
 */
namespace Common\Controller;
use Org\Util\Rbac;
use Common\Controller\BaseController;
class AdminbaseController extends BaseController{
	
	public function _initialize(){
		parent::_initialize();
		if(empty($_SESSION['admin'])){
			$this->redirect(U("Public/login"));
		}
		
	}
	
}