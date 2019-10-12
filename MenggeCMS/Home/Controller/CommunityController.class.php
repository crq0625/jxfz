<?php
/**
 *
 * 前台首页
 * @author zz
 *
 */
namespace Home\Controller;

class CommunityController extends PublicController {
	//选择具象群组
	public function commChoose() {
		//实例化group表
		$group = M('Group');
		//实例化member表
		$member = M('Member');
		//获取用户id
		$user_id = $_SESSION['userId'];
		$res = $member -> where("id = " . $user_id) -> select();
		//判断用户是否已加入群组
		if ($res[0]['group_id']) {
			$this -> commCircle();
		} else {
			//Group表和sysadmin表的左联查询
			$row = $group -> join(' LEFT JOIN jxfz_sysadmin ON jxfz_group.adminid = jxfz_sysadmin.id') -> field('jxfz_group.id,jxfz_group.name,jxfz_group.group_intro,jxfz_group.group_logo,jxfz_group.time,jxfz_group.toplimit,jxfz_group.usernum,jxfz_sysadmin.username') -> select();
			//将查询结果传递给前台
			$this -> assign('row', $row);
			//加载页面
			$this -> display('commChoose');
		}
	}

	//具象群组列表
	public function index() {
		//实例化group表
		$group = M('Group');
		//实例化member表
		$member = M('Member');
		//获取用户id
		$user_id = $_SESSION['userId'];
		$res = $member -> where("id = " . $user_id) -> select();
		//判断用户是否已加入群组
		if ($res[0]['group_id']) {
			$this -> commCircle();
		} else {
			//Group表和sysadmin表的左联查询
			$row = $group -> join(' LEFT JOIN jxfz_sysadmin ON jxfz_group.adminid = jxfz_sysadmin.id') -> field('jxfz_group.id,jxfz_group.name,jxfz_group.group_intro,jxfz_group.group_logo,jxfz_group.time,jxfz_group.toplimit,jxfz_group.usernum,jxfz_sysadmin.username') -> select();
			//将查询结果传递给前台
			$this -> assign('row', $row);
			//加载页面
			$this -> display('index');
		}
	}

	//加入群组
	public function addComm() {
		//获取群组id
		$group_id = I('id');
		$comm = D('Group');
		$res = $comm -> where("id =" . $group_id) -> select();
		$res[0]['usernum'] += 1;
		$datas['usernum'] = $res[0]['usernum'];
		//		dump($res);exit;
		//获取用户id
		$user_id = $_SESSION['userId'];
		$data['group_id'] = $group_id;
		//实例化Member表
		$member = D('Member');
		//将用户加入群组
		$row = $member -> where("id=" . $user_id) -> save($data);
		//判断是否加入成功
		if ($row) {
			//修改group中用户数量
			$r = $comm -> where("id = " . $group_id) -> save($datas);
			//			dump($comm -> getLastSql());exit;
			//加入成功跳转页面
			$this -> success("加入群组成功！");
		}
	}

	//群组圈子
	public function commCircle(){
		//获取用户id
		$user_id = $_SESSION['userId'];
		//实例化需要操作的表
		$member = D('Member');
		$work = D('Work');
		$comment = D('Comment');
		$workrate = D('Workrate');
		$event = D('Event');
		$observe = D('Observe');
		$community = D('Community');
		$comms = $this -> pageList($community,5,$where=null,"addtime desc");
		$comm = $comms['list'];
//		$comm = $community -> order("addtime desc") -> select();
		foreach($comm as $key => $value){
			$comm[$key]['content'] = json_decode($value['content'],true);
			//根据community表的id查询comment表
			$commentlist = $comment -> where("community_id = " . $value['id'] . " and pid=0") -> select();
			foreach($commentlist as $k => $v){
				$commentlist[$k]['reply'] = $comment -> where("pid = " . $v['id']) -> select();
				foreach($commentlist[$k]['reply'] as $ks => $vs){
					$memcomm = $member -> where("id = " . $vs['user_id']) -> select();
					$commentlist[$k]['reply'][$ks]['replyuser'] = $memcomm[0]['username'];
					$commentlist[$k]['reply'][$ks]['usertype'] = $memcomm[0]['type'];
					$memcomm1 = $member -> where("id = " . $v['user_id']) -> select();
					$commentlist[$k]['reply'][$ks]['replyusername'] = $memcomm1[0]['username'];
					
					
				}
				
				//根据comment表的id查询member表
				$memcomm = $member -> where("id = " . $v['user_id']) -> select();
				$commentlist[$k]['commuser'] = $memcomm[0]['username'];
				$commentlist[$k]['usertype'] = $memcomm[0]['type'];
				
			}
//			print_r($commentlist);exit;
			$comm[$key]['comment'] = $commentlist;
			//根据community表的id查询member表
			$mem = $member -> where("id = " . $value['user_id']) -> select();
			$comm[$key]['username'] = $mem[0]['username'];
			
			
		}
		
//		dump($comm);exit;dump($reply);exit;
		$this -> assign('page',$comms['page']);
//		print_r($comm);exit;
		$time = time();
		$this -> assign('comm',$comm);
		$this -> assign('now',$time);
		$this -> display('commcircle');
		
		
	}

	//回复评论
	public function reply(){
		$comment = D('Comment');
		$id = $_POST['pid'];
		$data = $_POST;
		$data['user_id'] = $_SESSION['userId'];
		$row = $comment -> add($data);
//		dump($comment -> getLastSql());exit;
		if($row){
			
			$this -> success('回复评论成功',U('Community/commCircle'));
		}
		
		
		
	}
	
	//群组圈子1
	public function commCircle1() {
		//获取用户id
		$user_id = $_SESSION['userId'];
		//实例化需要操作的表
		$member = D('Member');
		$work = D('Work');
		$comment = D('Comment');
		$workrate = D('Workrate');
		$event = D('Event');
		$observe = D('Observe');

		//查询用户所属群组的id
		$user = $member -> where("id = " . $user_id) -> select();
		$group_id = $user[0]['group_id'];
		//查询当前用户所属群的所有成员
		$memberAll = $member -> where("group_id =" . $group_id)  -> order("id desc")-> select();
		//获取当天时间起点和终点
		$y = date("Y");
		$m = date("m");
		$d = date("d");
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);

		//遍历群组内所有成员
		foreach ($memberAll as $key => $value) {
			//根据群组内成员的id，在work表中查询当天的案例主体
			$res = $work -> where("time<'$day_end' and time>='$day_start' and user_id = " . $value['id']) -> order("id desc") -> select();
			if (!empty($res)) {
				$row[$key] = $res[0];
			}
		}
		//遍历案例主题，获取对应案例主题的id，根据案例主题的id，查询用户每天具体事件
		foreach ($row as $k => $v) {
			//判断publish的值。publish=0则不查询该条数据，publish=1则查询workrate表，publish=2则查询event表，publish=3则查询observe表
			if ($v['publish'] == 1) {
				//根据work表的id查询workrate表
				$rows[$k] = $workrate -> where("work_id = " . $v['id']) -> select();
			} else if ($v['publish'] == 2) {
				//根据work表的id查询event表
				$rows[$k] = $event -> where("work_id = " . $v['id']) -> select();
			} else if ($v['publish'] == 3) {
				//根据work表的id查询observe表
				$rows[$k] = $observe -> where("work_id = " . $v['id']) -> select();
				foreach($rows[$k] as $ke => $va){
					$result1[$ke] = $observe -> where("pid =".$va['id']) -> select();
				}
				foreach ($result1 as $keys => $values) {
					$rows[$k][$keys]['observe_actions1'] = $values[0]['observe_actions'];
					$rows[$k][$keys]['observe_think1'] = $values[0]['observe_think'];
				}
			}
			//根据work表的id查询comment表
			$comm[$k] = $comment -> where("work_id = " . $v['id']) -> select();
			//根据work表的id查询member表
			$mem[$k] = $member -> where("id = " . $v['user_id']) -> select();
		}
		
		//查询用户名
		foreach ($comm as $ck => $cv) {
			foreach ($cv as $ckk => $cvv) {
				$userArr[$ck][$ckk] = $member -> where("id =" . $cvv['user_id']) -> select();
			}
		}
		//将评论用户名加入评论数组中
		foreach ($userArr as $uk => $uv) {
			foreach ($uv as $ukk => $uvv) {
				$comm[$uk][$ukk]['username'] = $uvv[0]['username'];
			}
		}
		foreach ($rows as $ks => $vs) {
			$maxk[] = array_keys($vs);
		}
		$maxk = max($maxk);
		$maxk = max($maxk);
		
		//将结果赋值给$result
		foreach ($row as $ks => $vs) {
			$vs['content'] = $rows[$ks];
			$vs['comment'] = $comm[$ks];
			$vs['username'] = $mem[$ks][0]['username'];
			$vs['summ'] = $rows[$ks][$maxk]['observe_summary'];
			$result[$ks] = $vs;
		}
//		print_r($row);exit;
		//获取今天时间
//		$result = rsort($result);
		$now = time();
		$this -> assign('now', $now);
		$this -> assign('result', $result);
		$this -> display('commcircle');
	}

	//用户评论
	public function addComment() {
		//获取用户id
		$user_id = $_SESSION['userId'];
		//实例化comment表
		$comment = D('Comment');
		if (IS_POST) {
			if ($_POST['content']) {
				//获取前台提交的数据
				$data = $_POST;
				$data['user_id'] = $user_id;
				$row = $comment -> add($data);
				if ($row) {
					$this -> success('评论成功', U('Community/index'));
				}
			} else {
				$this -> error('评论失败，请输入评论内容！');
			}
		} else {
			$this -> error('评论失败，请输入评论内容！');
		}
	}

	//日期选择
	public function eveChoose() {
		//实例化表格
		$member = D('Member');
		$work = D('Work');
		$workrate = D('workrate');
		$comment = D('Comment');

		$user_id = $_SESSION['userId'];
		$newDate = I('newDate');
		$r = strtotime($newDate);
		//获取某天时间起点和终点
		$y = date("Y", $r);
		$d = date("d", $r);
		$m = date("m", $r);
		$day_start = mktime(0, 0, 0, $m, $d, $y);
		$day_end = mktime(23, 59, 59, $m, $d, $y);

		//查询用户所属群的id
		$user = $member -> where("id = " . $user_id) -> select();
		$group_id = $user[0]['group_id'];
		//查询当前用户所属群的所有成员
		$memberAll = $member -> where("group_id =" . $group_id) -> select();
		//遍历群组内所有成员
		foreach ($memberAll as $key => $value) {
			//根据群组内成员的id，在work表中查询当天的案例主体
			$res = $work -> where("time<'$day_end' and time>='$day_start' and user_id = " . $value['id']) -> select();
			if (!empty($res)) {
				$row[$key] = $res[0];
			}
		}
		//遍历案例主题，获取对应案例主题的id，根据案例主题的id，查询用户每天具体事件
		foreach ($row as $k => $v) {
			//根据work表的id查询workrate表
			$rows[$k] = $workrate -> where("work_id = " . $v['id']) -> select();
			//根据work表的id查询comment表
			$comm[$k] = $comment -> where("work_id = " . $v['id']) -> select();
			//根据work表的id查询member表
			$mem[$k] = $member -> where("id = " . $v['user_id']) -> select();
		}
		//查询用户名
		foreach ($comm as $ck => $cv) {
			foreach ($cv as $ckk => $cvv) {
				$userArr[$ck][$ckk] = $member -> where("id =" . $cvv['user_id']) -> select();
			}
		}
		//将评论用户名加入评论数组中
		foreach ($userArr as $uk => $uv) {
			foreach ($uv as $ukk => $uvv) {
				$comm[$uk][$ukk]['username'] = $uvv[0]['username'];
			}
		}
		//将结果赋值给$result
		foreach ($row as $ks => $vs) {
			$vs['content'] = $rows[$ks];
			$vs['comment'] = $comm[$ks];
			$vs['username'] = $mem[$ks][0]['username'];
			$result[$ks] = $vs;
		}
		
		//获取某天时间
		$this -> assign('now', $r);
		$this -> assign('result', $result);
		$this -> display('somecommcircle');

	}

}
