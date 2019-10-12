<?php

namespace Common\Controller;
use Common\Controller\BaseController;

class JxfzbaseController extends BaseController{

	public function _initialize(){
		parent::_initialize();
	}
	//获取某天开始时间的时间戳
	public function getTimeStart($nDate) {
		$y = date("Y", $nDate);
		$m = date("m", $nDate);
		$d = date("d", $nDate);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		return $day_start;
	}
	
	//获取某天结束时间的时间戳
	public function getTimeEnd($nDate) {
		$y = date("Y", $nDate);
		$m = date("m", $nDate);
		$d = date("d", $nDate);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		return $day_end;
	}
}