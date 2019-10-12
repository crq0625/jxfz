<?php
/**
 *
 * 前台首页
 * @author zz
 */
namespace Home\Controller;
class WorkController extends PublicController {
	
	
	//每日具象
	public function index() {
		$work = D('work');
		$userid = $_SESSION['userId'];
		//获取当天时间起点和终点
		$time = time();
		$day_start = $this -> getTimeStart($time);
		$day_end = $this -> getTimeEnd($time);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$this -> assign('addtime', $time);
		$this -> assign('workId', $row[0]['id']);
		$this -> assign('subject', $row[0]['subject']);
		$this -> assign('target', $row[0]['target']);
		$this -> assign('summary', $row[0]['summary']);
		$this -> assign('addtime', $row[0]['time']);
		//加载视图
		$this -> edit();
	}

	//添加案例主体
	public function add() {
		$user_id = $_SESSION['userId'];
		$work = D('work');

		//判断是否有数据提交
		if (IS_POST) {
			//判断案例主体和目标是否提交
			if (!empty($_POST['who']) && !empty($_POST['goal'])) {
				if (empty($_POST['chooseDate'])) {
					$data['time'] = time();
				} else {
					$data['time'] = $_POST['chooseDate'];
				}
				//获取当天时间起点和终点
				$y = date("Y");
				$m = date("m");
				$d = date("d");
				$day_start = mktime(0, 0, 0, $m, $d, $y);
				$day_end = mktime(23, 59, 59, $m, $d, $y);
				//获取案例主体和案例目标
				$data['subject'] = $_POST['who'];
				$data['target'] = $_POST['goal'];
				$data['user_id'] = $user_id;
				$mem = $work -> where('user_id = ' . $user_id) -> select();
				if ($mem) {
					$rows = $work -> where("time<'$day_end' and time>='$day_start' and user_id = " . $user_id) -> save($data);
				} else {
					//将数据添加到数据表
					$rows = $work -> add($data);
				}

				if ($rows) {
					$this -> success("添加主题成功！", U("Work/edit", "chooseDate=" . $data['time']));
				}
			}
		}
	}

	//完成案例的进度
	public function edit() {
		$work = D('work');
		$workrate = D('workrate');
		$event = D('event');
		$observe = D('Observe');
		$userid = $_SESSION['userId'];
		if (!empty($_GET['chooseDate'])) {
			$chooseDate = I('chooseDate');
		} else if (!empty($_POST['addChooseDate'])) {
			$addChooseDate = $_POST['addChooseDate'];
			$chooseDate = $addChooseDate;
		} else {
			$chooseDate = time();
		}
		//获取某天时间起点和终点
		$day_start = $this -> getTimeStart($chooseDate);
		$day_end = $this -> getTimeEnd($chooseDate);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		if (!empty($row)) {
			$id = $row[0]['id'];
			$rows = $workrate -> where("work_id = $id") -> select();
			if (IS_POST) {
				$data['starttime'] = $_POST['starttime'];
				$data['endtime'] = $_POST['endtime'];
				$data['actions'] = $_POST['done'];
				$data['results'] = $_POST['effect'];
				$data['inspire'] = $_POST['think'];
				$data['addtime'] = time();
				$data['work_id'] = $id;
				$rows = $workrate -> add($data);
				if ($rows) {
					$this -> redirect('work/edit', 'chooseDate=' . $chooseDate);
					exit ;
				}
			}
			$result = $workrate -> where("work_id = $id") -> select();

			$result1 = $event -> where("work_id = $id") -> select();

			$result2 = $observe -> where("work_id = $id") -> select();
			foreach ($result2 as $key => $value) {
				$result21[$key] = $observe -> where("pid =" . $value['id']) -> select();
			}
			foreach ($result21 as $k => $v) {
				$result2[$k]['observe_actions1'] = $v[0]['observe_actions'];
				$result2[$k]['observe_think1'] = $v[0]['observe_think'];
				//				$maxk[] = $k;
			}
			//			$maxk = max($maxk);
			//			$summ = $result2[$maxk]['observe_summary'];
			$this -> assign('result2', $result2);

			//			$this -> assign('summ', $summ);
			$this -> assign('result1', $result1);
			$this -> assign('result', $result);

			$this -> assign('workId', $row[0]['id']);
			$this -> assign('subject', $row[0]['subject']);
			$this -> assign('target', $row[0]['target']);
			$this -> assign('summary', $row[0]['summary']);
			$this -> assign('addtime', $row[0]['time']);
			$this -> display('edit');
		} else {
			$this -> assign('addtime', $chooseDate);
			$this -> display('edit');
		}
	}

	//事件具象列表
	public function eventList() {
		$event = D('Event');
		$work = D('Work');
		$userid = $_SESSION['userId'];
		if (!empty($_GET['chooseDate'])) {
			$chooseDate = I('chooseDate');
		} else if (!empty($_POST['addChooseDate'])) {
			$addChooseDate = $_POST['addChooseDate'];
			$chooseDate = $addChooseDate;
		} else {
			$chooseDate = time();
		}
		//获取某天时间起点和终点
		$y = date("Y", $chooseDate);
		$m = date("m", $chooseDate);
		$d = date("d", $chooseDate);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$id = $row[0]['id'];
		if (empty($row[0]['time'])) {
			$addtime = time();
		} else {
			$addtime = $row[0]['time'];
		}
		$result1 = $event -> where("work_id = $id") -> select();
		//		dump($result1);exit;
		$this -> assign('result1', $result1);
		$this -> assign('row', $row[0]);
		$this -> assign('addtime', $addtime);
		$this -> display('eventadd');
	}

	//观察者具象列表
	public function observeList() {
		$observe = D('Observe');
		$work = D('Work');
		$userid = $_SESSION['userId'];
		//获取某天时间起点和终点
		$time = time();
		$day_start = $this -> getTimeStart($time);
		$day_end = $this -> getTimeEnd($time);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$id = $row[0]['id'];
		$oblist = $observe -> where('work_id = ' . $id) -> select();
		$this -> assign('oblist', $oblist);
		$this -> assign('addtime', $time);
		$this -> assign('row', $row[0]);
		$this -> display('observeList');
	}

	//观察者具象列表
	public function observeList1() {
		$observe = D('Observe');
		$work = D('Work');
		$userid = $_SESSION['userId'];
		if (!empty($_GET['chooseDate'])) {
			$chooseDate = I('chooseDate');
		} else if (!empty($_POST['addChooseDate'])) {
			$addChooseDate = $_POST['addChooseDate'];
			$chooseDate = $addChooseDate;
		} else {
			$chooseDate = time();
		}
		//获取某天时间起点和终点
		$day_start = $this -> getTimeStart($chooseDate);
		$day_end = $this -> getTimeEnd($chooseDate);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$id = $row[0]['id'];
		$result2 = $observe -> where("work_id = $id") -> select();
		foreach ($result2 as $key => $value) {
			$result21[$key] = $observe -> where("pid =" . $value['id']) -> select();
		}
		foreach ($result21 as $k => $v) {
			$result2[$k]['observe_actions1'] = $v[0]['observe_actions'];
			$result2[$k]['observe_think1'] = $v[0]['observe_think'];
		}
		$maxk = max($maxk);
		if (empty($row[0]['time'])) {
			$addtime = time();
		} else {
			$addtime = $row[0]['time'];
		}
		$this -> assign('result2', $result2);
		$this -> assign('workId', $row[0]['id']);
		$this -> assign('subject', $row[0]['subject']);
		$this -> assign('target', $row[0]['target']);
		$this -> assign('summary', $row[0]['summary']);
		$this -> assign('addtime', $addtime);
		$this -> display('observeList');
	}

	//案例总结添加
	public function addsum() {
		$work = D('work');
		if (IS_POST) {
			$id = $_POST['workId'];
			$data['subject'] = $_POST['who'];
			$data['target'] = $_POST['goal'];
			if (!empty($_POST['summary'])) {
				$data['summary'] = $_POST['summary'];
			} else {
				$data['summary'] = null;
			}
			//			$res = $work -> where("id = $id") -> select();
			//			if (!empty($res)) {
			$rows = $work -> where("id = $id") -> save($data);
			if ($rows) {
				$this -> success('修改主题成功！', U("Work/edit"));
			} else {

				$this -> error('修改主题成功！');
			}
			//			}
			//			else {
			//				$data['time'] = time();
			//				$data['user_id'] = $_SESSION['userId'];
			//				$rows = $work -> add($data);
			//				if ($rows) {
			//					$this -> success('添加成功！', U("Work/edit"));
			//				}
			//			}

		} 
	}
	//时间具象修改
	public function editAction(){
		$workrate = D('Workrate');
		$id = I('id');
		$work = D('work');
		$userid = $_SESSION['userId'];
		//获取当天时间起点和终点
		$time = time();
		$day_start = $this -> getTimeStart($time);
		$day_end = $this -> getTimeEnd($time);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();

		$work_id = $row[0]['id'];
		$result = $workrate -> where("work_id = $work_id") -> select();
		foreach ($result as $k => $v) {
			if ($v['id'] == $id) {
				$result[$k]['edit'] = 1;
			}
		}
		$this -> assign('result', $result);
		$this -> assign('row', $row[0]);
		$this -> assign('addtime', $time);
		$this -> display('editAction');
		
	}
	
	
	
	//时段行动修改
	public function editAction1() {
		$work = D('workrate');
		$id = I('id');
		$data['status'] = 1;
		$rows = $work -> where("id = $id") -> save($data);
		if ($rows) {
			$work = D('work');
			$workrate = D('workrate');
			$userid = $_SESSION['userId'];
			if (!empty($_GET['chooseDate'])) {
				$chooseDate = I('chooseDate');
			} else {
				$chooseDate = time();
			}
			//获取当天时间起点和终点
			$day_start = $this -> getTimeStart($chooseDate);
			$day_end = $this -> getTimeEnd($chooseDate);
			$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
			$id = $row[0]['id'];
			$rows = $workrate -> where("work_id = $id") -> select();
			if (IS_POST) {
				$data['starttime'] = $_POST['starttime'];
				$data['endtime'] = $_POST['endtime'];
				$data['actions'] = $_POST['done'];
				$data['results'] = $_POST['effect'];
				$data['inspire'] = $_POST['think'];
				$data['addtime'] = time();
				$data['work_id'] = $id;
				$rows = $workrate -> add($data);
			}
			$result = $workrate -> where("work_id = $id") -> select();
			$this -> assign('result', $result);
			$this -> assign('workId', $row[0]['id']);
			$this -> assign('subject', $row[0]['subject']);
			$this -> assign('target', $row[0]['target']);
			$this -> assign('summary', $row[0]['summary']);
			$this -> assign('addtime', $row[0]['time']);
		}
		$this -> display('editaction');
	}

	//修改事件具象内容页面
	public function editEvent() {
		$event = D('Event');
		$id = I('id');
		$work = D('work');
		$userid = $_SESSION['userId'];
		//获取当天时间起点和终点
		$time = time();
		$day_start = $this -> getTimeStart($time);
		$day_end = $this -> getTimeEnd($time);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();

		$work_id = $row[0]['id'];
		$result = $event -> where("work_id = $work_id") -> select();
		foreach ($result as $k => $v) {
			if ($v['id'] == $id) {
				$result[$k]['edit'] = 1;
			}
		}
		$this -> assign('result1', $result);
		$this -> assign('row', $row[0]);
		$this -> assign('addtime', $time);
		$this -> display('eventadd');
	}

	//修改观察者具象内容页面
	public function editObserve() {
		$observe = D('Observe');
		$id = I('id');
		$work = D('work');
		$userid = $_SESSION['userId'];
		//获取当天时间起点和终点
		$time = time();
		$day_start = $this -> getTimeStart($time);
		$day_end = $this -> getTimeEnd($time);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();

		$work_id = $row[0]['id'];
		$result = $observe -> where("work_id = $work_id") -> select();
		foreach ($result as $k => $v) {
			if ($v['id'] == $id) {
				$result[$k]['edit'] = 1;
			}
		}
		$this -> assign('oblist', $result);
		$this -> assign('row', $row[0]);
		$this -> assign('addtime', $time);
		$this -> display('observeList');
	}

	//修改时段行动内容
	public function editActCont1() {
		$work = D('workrate');
		$chooseDate = $_POST['chooseDate'];
		if (IS_POST) {
			if (!empty($_POST['starttime']) && !empty($_POST['endtime'])) {
				$id = $_POST['workRateId'];
				$data['status'] = 0;
				$data['starttime'] = $_POST['starttime'];
				$data['endtime'] = $_POST['endtime'];
				$data['actions'] = $_POST['done'];
				$data['results'] = $_POST['effect'];
				$data['inspire'] = $_POST['think'];
				$rows = $work -> where("id = $id") -> save($data);
				if ($rows) {
					$this -> success('修改成功！', U("Work/edit", "chooseDate=" . $chooseDate));
				}
			} else {
				$this -> error('请选择开始或结束时间！');
			}
		}
	}
	
	//修改时段行动内容
	public function editActCont() {
		$workrate = D('workrate');
		$id = $_GET['id'];
		$data['edittime'] = time();
		$data['starttime'] = $_POST['starttime'];
		$data['endtime'] = $_POST['endtime'];
		$data['actions'] = $_POST['done'];
		$data['results'] = $_POST['effect'];
		$data['inspire'] = $_POST['think'];
		$rows = $workrate -> where("id = $id") -> save($data);
		if ($rows) {
			$this -> success('修改成功！', U("Work/edit"));
		}
	}
	

	//保存修改事件具象内容
	public function editEventCont() {
		$event = D('Event');
		$id = $_GET['id'];
		$data = $_POST;
		$data['edittime'] = time();
		$rows = $event -> where("id = $id") -> save($data);
		if ($rows) {
			$this -> success('修改成功！', U("Work/eventList"));
		}
	}

	//保存修改观察者具象内容
	public function editObserveCont() {
		$observe = D('Observe');
		$id = $_GET['id'];
		$data = $_POST;
		$data['edittime'] = time();
		$rows = $observe -> where("id = $id") -> save($data);
		if ($rows) {
			$this -> success('修改成功！', U("Work/observeList"));
		}
	}

	//时段行动删除
	public function editDel() {
		//获取本条行动的id
		$id = I('id');
		//实例化数据库模型
		$workrate = D('workrate');
		$row = $workrate -> where("id = $id") -> select();
		$times = $row[0]['addtime'];
		$r = $workrate -> where("id = $id") -> delete();
		if ($r) {
			$this -> success('删除成功！', U("Work/index", "chooseDate=" . $times));
		} else {
			$this -> error('删除失败！');
		}
	}

	//删除事件具象
	public function eventDel() {
		//获取本条行动的id
		$id = I('id');
		//实例化数据库模型
		$event = D('Event');
		$row = $event -> where("id = $id") -> select();
		$times = $row[0]['addtime'];
		$r = $event -> where("id = $id") -> delete();
		if ($r) {
			$this -> success('删除成功！', U("Work/eventList", "chooseDate=" . $times));
		} else {
			$this -> error('删除失败！');
		}

	}

	//删除观察者具象的一条数据
	public function observeDel() {
		//获取本条行动的id
		$id = I('id');
		//实例化数据库模型
		$observe = D('Observe');
		$r = $observe -> where("id = $id") -> delete();
		if ($r) {
			$this -> success('删除成功！', U("Work/observeList"));
		} else {
			$this -> error('删除失败！');
		}
	}

	//案例列表
	public function worklist() {
		$work = D('work');
		$workrate = D('workrate');
		$userid = $_SESSION['userId'];
		$chooseDate = I('chooseDate');
		//获取当天时间起点和终点
		$y = date("Y", $chooseDate);
		$m = date("m", $chooseDate);
		$d = date("d", $chooseDate);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$work_id = $row[0]['id'];
		$rows = $workrate -> where(" work_id = $work_id") -> select();
		$clientDate = $row[0]['time'];
		$shareLink = U('Work/shareDay', 'chooseDate=' . $chooseDate);
		$this -> assign('shareLink', $shareLink);
		$this -> assign('clientDate', $clientDate);
		$this -> assign('row', $row);
		$this -> assign('rows', $rows);
		$this -> display('worklist');
	}

	//选择某天的具象练习作业
	public function dateChoose() {
		$userid = $_SESSION['userId'];
		$newDate = I('newDate');
		$r = strtotime($newDate);
		//获取某天时间起点和终点
		$y = date("Y", $r);
		$d = date("d", $r);
		$m = date("m", $r);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		$work = D('work');
		$workrate = D('workrate');
		$row = $work -> where("time<'$day_end' and time>'$day_start' and user_id = '$userid'") -> select();
		$work_id = $row[0]['id'];
		$rows = $workrate -> where("work_id = $work_id") -> select();
		//分享链接
		$shareLink = U('Work/shareDay', 'chooseDate=' . $r);
		$this -> assign('shareLink', $shareLink);
		$this -> assign('row', $row);
		$this -> assign('rows', $rows);
		$this -> assign('clientDate', $r);
		$this -> display('worklist');
	}

	//分享链接
	public function shareDay() {
		$work = D('work');
		$workrate = D('workrate');
		$userid = $_SESSION['userId'];
		$chooseDate = I('chooseDate');
		//获取当天时间起点和终点
		$y = date("Y", $chooseDate);
		$m = date("m", $chooseDate);
		$d = date("d", $chooseDate);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$work_id = $row[0]['id'];
		$rows = $workrate -> where(" work_id = $work_id") -> select();
		$clientDate = $row[0]['time'];
		$this -> assign('clientDate', $clientDate);
		$this -> assign('row', $row);
		$this -> assign('rows', $rows);
		$this -> display('share');
	}

	//三种具象方式
	public function workmore() {

		$this -> display('workmore');
	}

	//事件添加具象
	public function eventadd() {
		$work = D('work');
		$event = D('event');
		$userid = $_SESSION['userId'];
		if (!empty($_GET['chooseDate'])) {
			$chooseDate = I('chooseDate');
		} else if (!empty($_POST['addChooseDate'])) {
			$addChooseDate = $_POST['addChooseDate'];
			$chooseDate = $addChooseDate;
		} else {
			$chooseDate = time();
		}
		//获取某天时间起点和终点
		$y = date("Y", $chooseDate);
		$m = date("m", $chooseDate);
		$d = date("d", $chooseDate);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		//		dump($row);exit;
		if (!empty($row)) {
			$id = $row[0]['id'];
			$rows = $event -> where("work_id = $id") -> select();
			if (IS_POST) {
				$data['event'] = $_POST['event'];
				$data['actions'] = $_POST['actions'];
				$data['results'] = $_POST['results'];
				$data['inspire'] = $_POST['inspire'];
				$data['addtime'] = time();
				$data['work_id'] = $id;
				$rows = $event -> add($data);
				if ($rows) {
					$this -> redirect('Work/eventList');
					exit ;
				}
			}
			//			$this -> edit();
		} else {
			$this -> assign('today', $chooseDate);
			$this -> display('index');
		}

	}

	//添加被具象人的具象内容
	public function addObserveCover() {
		//实例化被具象人表表
		$observeCover = D('Observecover');
		$work = D('work');
		$userid = $_SESSION['userId'];
		$time = time();
		$day_start = $this -> getTimeStart();
		$day_end = $this -> getTimeEnd();
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$work_id = $row[0]['id'];
		if (IS_POST) {
			$_POST['work_id'] = $work_id;
			$res = $observeCover -> add($_POST);
			if ($res) {
				$this -> redirect('Work/observeList');
			}
		}
	}

	//添加具象支持人的具象内容
	public function addObserveSup() {
		//实例化被具象人表表
		$observeCover = D('Observe');
		$work = D('work');
		$userid = $_SESSION['userId'];
		$time = time();
		$day_start = $this -> getTimeStart();
		$day_end = $this -> getTimeEnd();
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		$work_id = $row[0]['id'];
		if (IS_POST) {
			$_POST['work_id'] = $work_id;
			$res = $observeCover -> add($_POST);
			if ($res) {
				$this -> redirect('Work/observeList');
			}
		}
	}

	//添加观察者具象
	public function addobserve() {
		$work = D('Work');
		$observe = D('observe');
		$userid = $_SESSION['userId'];
		//用户选择某天查看内容
		if (!empty($_GET['chooseDate'])) {
			$chooseDate = I('chooseDate');
		} else if (!empty($_POST['addChooseDate'])) {
			$addChooseDate = $_POST['addChooseDate'];
			$chooseDate = $addChooseDate;
		} else {
			$chooseDate = time();
		}
		//获取某天时间起点和终点
		$day_start = $this -> getTimeStart($chooseDate);
		$day_end = $this -> getTimeEnd($chooseDate);
		$row = $work -> where("time<'$day_end' and time>='$day_start' and user_id = '$userid'") -> select();
		if (!empty($row)) {
			$id = $row[0]['id'];
			$rows = $observe -> where("work_id = $id") -> select();
			if (IS_POST) {
				$data['observe_actions'] = $_POST['observe_actions'];
				$data['observe_think'] = $_POST['observe_think'];
				//				$data['observe_summary'] = $_POST['observe_summary'];
				$data['observe_actions1'] = $_POST['observe_actions1'];
				$data['observe_think1'] = $_POST['observe_think1'];
				$data['work_id'] = $id;
				$data['addtime'] = time();
				$rows = $observe -> add($data);
				if ($rows) {
					$this -> redirect('Work/observeList');
				}
				//				if (!empty($_POST['observe_actions1']) && !empty($_POST['observe_think1'])) {
				//					$data1['pid'] = $observe -> getLastInsID();
				//					$data1['observe_actions'] = $_POST['observe_actions1'];
				//					$data1['observe_think'] = $_POST['observe_think1'];
				//					$data1['type'] = 1;
				//					$res = $observe -> add($data1);
				//				}
				//				if ($rows) {
				//					$this -> redirect('Work/observeList');
				//				}
			}
		} else {
			$this -> assign('today', $chooseDate);
			$this -> display('observeList');
		}

	}

	//发布时间具象
	public function publishTime1() {
		//获取workid
		$workId = $_GET['workId'];
		$work = D('Work');
		$res = $work -> where("id=" . $workId) -> select();
		if ($res[0]["subject"]) {
			if ($res[0]['publish'] == 1) {
				$this -> error("该时间具象已经发布，无需再发布！");
			} else {
				$data['publish'] = 1;
				$row = $work -> where("id = " . $workId) -> save($data);
				if ($row) {
					$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/index"));
				}
			}

		} else {
			$this -> error("主题未填写，请填写主题后再发布！");
		}

	}

	//发布时间具象
	public function publishTime() {
		//获取workid
		$work_id = $_GET['workId'];
		$user_id = $_SESSION['userId'];
		$work = D('Work');
		$workrate = D('Workrate');
		$community = D('Community');
		$res = $work -> where("id=" . $work_id) -> select();
		$comf = $community -> where("publish =1 and work_id=" . $work_id) -> select();
		//		if($comf){
		//			$ratelist = $workrate -> where('work_id ='.$work_id) -> select();
		//			$result[0] = $res;
		//			$result[1] = $ratelist;
		//			$data['content'] = json_encode($result);
		//			$data['addtime'] = time();
		//			$data['user_id'] = $user_id;
		//			$data['work_id'] = $work_id;
		//			$data['publish'] = 1;
		//			$result = $community -> where("id = ".$comf[0]['id']) -> save($data);
		//			if($result){
		//				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));
		//			}
		//		}else{
		$ratelist = $workrate -> where('work_id =' . $work_id) -> select();
		if(!empty($ratelist)){
			$result[0] = $res;
			$result[1] = $ratelist;
			$data['content'] = json_encode($result);
			$data['addtime'] = time();
			$data['user_id'] = $user_id;
			$data['work_id'] = $work_id;
			$data['publish'] = 1;
			$row = $community -> add($data);
			if ($row) {
				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));
			}
		}else{
			$this -> error("您没有填写时间具象内容，请填写后在发布");
		}
		//		}
	}

	//发布事件具象
	public function publishEvent() {
		//获取workid
		$work_id = $_GET['workId'];
		$user_id = $_SESSION['userId'];
		$work = D('Work');
		$event = D('Event');
		$community = D('Community');
		$res = $work -> where("id=" . $work_id) -> select();
		//		$comf = $community -> where("publish =2 and work_id=".$work_id) -> select();
		//		if($comf){
		//			$eventlist = $event -> where('work_id ='.$work_id) -> select();
		//			$result[0] = $res;
		//			$result[1] = $eventlist;
		//			$data['content'] = json_encode($result);
		//			$data['addtime'] = time();
		//			$data['user_id'] = $user_id;
		//			$data['work_id'] = $work_id;
		//			$data['publish'] = 2;
		//			$result = $community -> where("id = ".$comf[0]['id']) -> save($data);
		//			if($result){
		//				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));
		//			}
		//		}else{
		$eventlist = $event -> where('work_id =' . $work_id) -> select();
		if(!empty($eventlist)){
			$result[0] = $res;
			$result[1] = $eventlist;
			$data['content'] = json_encode($result);
			$data['addtime'] = time();
			$data['user_id'] = $user_id;
			$data['work_id'] = $work_id;
			$data['publish'] = 2;
			$row = $community -> add($data);
			if ($row) {
				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));

			}
			
		}else{
			$this -> error("您没有填写事件具象内容，请填写后在发布");
		}
		

		//		}
	}

	//发布观察者具象
	public function publishObserve() {
		//		获取workid
		$work_id = $_GET['workId'];
		$user_id = $_SESSION['userId'];
		$work = D('Work');
		$observe = D('Observe');
		$community = D('Community');
		$res = $work -> where("id=" . $work_id) -> select();
		$comf = $community -> where("publish =3 and work_id=" . $work_id) -> select();
		//		if($comf){
		//			$observelist = $observe -> where('work_id ='.$work_id) -> select();
		//			$result[0] = $res;
		//			$result[1] = $observelist;
		//			$data['content'] = json_encode($result);
		//			$data['addtime'] = time();
		//			$data['user_id'] = $user_id;
		//			$data['work_id'] = $work_id;
		//			$data['publish'] = 3;
		//			$result = $community -> where("id = ".$comf[0]['id']) -> save($data);
		//			if($result){
		//				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));
		//			}
		//		}else{
		$observelist = $observe -> where('work_id =' . $work_id) -> select();
		if(!empty($observelist)){
			$result[0] = $res;
			$result[1] = $observelist;
			$data['content'] = json_encode($result);
			$data['addtime'] = time();
			$data['user_id'] = $user_id;
			$data['work_id'] = $work_id;
			$data['publish'] = 3;
			$row = $community -> add($data);
			if ($row) {
				$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/commCircle"));
	
			}
		}else{
			$this -> error("您没有填写观察者具象内容，请填写后在发布");
		}
		//		}
	}

	//发布事件具象
	public function publishEvent1() {
		//获取workid
		$workId = $_GET['workId'];
		$work = D('Work');
		$res = $work -> where("id=" . $workId) -> select();
		if ($res[0]["subject"]) {
			if ($res[0]['publish'] == 2) {
				$this -> error("该时间具象已经发布，无需再发布！");
			} else {
				$data['publish'] = 2;
				$row = $work -> where("id = " . $workId) -> save($data);
				if ($row) {
					$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/index"));
				}
			}
		} else {
			$this -> error("主题未填写，请填写主题后再发布！");
		}
	}

	//发布观察者具象
	public function publishObserve1() {
		//获取workid
		$workId = $_GET['workId'];
		$work = D('Work');
		$res = $work -> where("id=" . $workId) -> select();
		if ($res[0]["subject"]) {
			if ($res[0]['publish'] == 3) {
				$this -> error("该时间具象已经发布，无需再发布！");
			} else {
				$data['publish'] = 3;
				$row = $work -> where("id = " . $workId) -> save($data);
				if ($row) {
					$this -> success('发布成功，即将跳转至具象朋友圈', U("Community/index"));
				}
			}

		} else {
			$this -> error("主题未填写，请填写主题后再发布！");
		}
	}

}
