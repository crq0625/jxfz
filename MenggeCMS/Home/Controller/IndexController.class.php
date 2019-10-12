<?php
/**
 * 
 * 前台首页
 * @author zz
 *
 */
 namespace Home\Controller;
 
class IndexController extends PublicController {
    public function index(){
		
		$this->display('Public/login');
    }
	public function reg(){
		
		$this->display();
	}
    
}