<?php
/**
 * 
 * 前台首页
 * @author zz
 *
 */
 namespace Home\Controller;
 
class ProjectController extends PublicController {
	
    public function index(){
		
		$this->display('index');
    }

    public function edit(){
		
		$this->display('edit');
    }
	
	public function prolist(){
		
		$this->display('prolist');
    }
	
}