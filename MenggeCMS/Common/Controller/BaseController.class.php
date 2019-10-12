<?php
/**
 * 项目基类文件
 * @author jack
 * 模块通用方法全部放到此文件
 */
namespace Common\Controller;
use Think\Controller;
class BaseController extends Controller{
	
	public function _initialize(){
		$this->assign('system_name',C('SYSTEM_NAME'));
	}


	/**
	 * @param int $count	总条数
	 * @param int 当前页码数
	 * @param int $page_size 每页显示的条数
	 * @param string $url	首页地址
	 * @return string
	 */
	public function show_page($count,$page,$page_size,$url)
	{
		$page_count  = ceil($count/$page_size);  //计算得出总页数
	
		$init=1;
		$page_len=7;
		$max_p=$page_count;
		$pages=$page_count;
	
		//判断当前页码
		$page=(empty($page)||$page<0)?1:$page;
		//去掉url中原先的page参数以便加入新的page参数
		$parsedurl=parse_url($url);
		$url_query = isset($parsedurl['query']) ? $parsedurl['query']:'';
		if($url_query != ''){
			$url_query = preg_replace("/(^|&)page=$page/",'',$url_query);
			$url = str_replace($parsedurl['query'],$url_query,$url);
			if($url_query != ''){
				$url .= '&';
			}
		} else {
			$url .= '?';
		}
	
		//分页功能代码
		$page_len = ($page_len%2)?$page_len:$page_len+1;  //页码个数
		$pageoffset = ($page_len-1)/2;  //页码个数左右偏移量
	
		$navs='';
		if($pages != 0){
			if($page!=1){
				$navs.="<a href=\"".$url."page=1\">首页</a>&nbsp; ";        //第一页
				$navs.="<a href=\"".$url."page=".($page-1)."\">上页</a>&nbsp;"; //上一页
			} else {
				$navs .= "<span>首页&nbsp;&nbsp;</span>";
				$navs .= "<span>上页&nbsp;&nbsp;</span>";
			}
			if($pages>$page_len)
			{
				//如果当前页小于等于左偏移
				if($page<=$pageoffset){
					$init=1;
					$max_p = $page_len;
				}
				else  //如果当前页大于左偏移
				{
					//如果当前页码右偏移超出最大分页数
					if($page+$pageoffset>=$pages+1){
						$init = $pages-$page_len+1;
					}
					else
					{
						//左右偏移都存在时的计算
						$init = $page-$pageoffset;
						$max_p = $page+$pageoffset;
					}
				}
			}
			for($i=$init;$i<=$max_p;$i++)
			{
				if($i==$page){$navs.="<span class='current'>".$i.'</span>';}
				else {$navs.=" <a href=\"".$url."page=".$i."\">".$i."</a>";}
			}
			if($page!=$pages)
			{
				$navs.=" <a href=\"".$url."page=".($page+1)."\">下页</a> ";//下一页
				$navs.="<a href=\"".$url."page=".$pages."\">末页</a>";    //最后一页
			} else {
				$navs .= "<span class='disabled'>下页</span>";
				$navs .= "<span class='disabled'>末页</span>";
			}
			//$navs .= '总共有'.$page_count.'页，共有<span style="color:red">'.$count.'</span>条数据&nbsp;&nbsp;';
			return "$navs";
		}
		}
		
	/**
	 * 显示验证码
	 * @param type $length 验证码的长度
	 * @param type $height 图片的高度
	 * @param type $fontSize 文字的大小
	 * @param type $useCurve
	 * @param type $fonttf 字体
	 * @echo Image
	 */
	public function show_verify() {
		$verify = new \Think\Verify(array(
				'length'	 => I('get.length', 4),
				'imageH'	 => I('get.height', 50),
				'imageW'	 => I('get.width', 238),
				'fontSize'	 => I('get.size', 20),
				'useCurve'	 => FALSE,
				'fontttf'	 => '5.ttf',
		));
		$verify->entry(1);
	}
	/**
	 * 分页函数
	 */
	public function pageList($model,$pageSize=10,$where=null,$order=null){
		$User = $model; // 实例化User对象
		$count = $User->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,$pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
//		dump($model->getLastSql());exit;
		$pageData['list'] = $list;
		$pageData['page'] = $show; 
		return $pageData;
//		$this->assign('list',$list);// 赋值数据集
//		$this->assign('page',$show);// 赋值分页输出
	}
	/**
	 * 单个文件上传
	 * @param string $fileName	上传的 文件
	 * @return 上传文件的信息
	 */
	public function Fupload() {
		$upload = new \Think\Upload (); // 实例化上传类
		$upload->maxSize = 3145728; // 设置附件上传大小
		$upload->exts = array (
				'doc',
				'docx',
				'pdf' 
		); // 设置附件上传类型
		$upload->rootPath = './Upload/'.MODULE_NAME.'/file/'; // 设置附件上传根目录
		$info = $upload->upload();
		if (! $info) { // 上传错误提示错误信息
			$this->error ( $upload->getError () );
		} else { // 上传成功 获取上传文件信息
			return $info;
		}
	}
	/**
	 * 图片上传
	 * @return 返回上传的文件信息
	 */
	public function Pupload(){
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array(
				'jpg',
				'jpeg',
				'png',
				'gif'
		);//设置图片上传类型
		$upload->rootPath = './Upload/'.MODULE_NAME.'/pic/';//设置图片上传路径
		$info = $upload->upload();
		if (! $info) { // 上传错误提示错误信息
			$this->error ( $upload->getError () );
		} else { // 上传成功 获取上传文件信息
			return $info;
		}
	}
	/**
	 * 视频上传
	 * @return 返回上传的视频信息
	 */
	public function Nupload(){
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728000;
		$upload->exts = array(
				'gif',
				'mp4',
				'avi',
				'm2t',
				'wmv'
		);//设置广告上传类型
		$upload->rootPath = './Upload/'.MODULE_NAME.'/adv/';//设置广告上传路径
		$info = $upload->upload();
		if (! $info) { // 上传错误提示错误信息
			return $upload->getError ();
		} else { // 上传成功 获取上传文件信息
			return $info;
		}
	}
	/**
	 * 判断上传的文件是不是pdf，不是pdf的转成pdf
	 * @param array $info	文件的信息
	 * @return array 文件页数和文件路径
	 */
	public function getpdf($info){
		$type = $info['ext'];
		if($type=='pdf'){
			$filename = $info['savename'];
			$path = './Upload/'.MODULE_NAME.'/file/'.$info['savepath'].$info['savename'];
			$filepath = '/Upload/'.MODULE_NAME.'/file/'.$info['savepath'].$info['savename'];
		}else{
			$arr = explode('.', $info['savename']);
			$filename = $arr[0].'.pdf';
			//echo "unoconv --server 127.0.0.1 --port 8100 --stdout -f pdf  /alidata/www/sinzk/Uploads/Weixin/file/".$info['savepath'].$info['savename']." > /alidata/www/sinzk/Uploads/Weixin/file/".$info['savepath'].$filename;exit;
			shell_exec("unoconv --server 127.0.0.1 --port 8100 --stdout -f pdf  /alidata/www/nsinzk/Upload/".MODULE_NAME."/file/".$info['savepath'].$info['savename']." > /alidata/www/nsinzk/Upload/".MODULE_NAME."/file/".$info['savepath'].$filename);
			//shell_exec("unoconv --server localhost --port 2002 --stdout -f pdf  /alidata/www/sinzk/file/b56c4aa4403dd6125c623827a19036c8.doc > /alidata/www/sinzk/file/xx.pdf");
			$path = './Upload/'.MODULE_NAME.'/file/'.$info['savepath'].$filename;
			$filepath =  '/Upload/'.MODULE_NAME.'/file/'.$info['savepath'].$filename;
		}
		return array('filename'=>$filepath,'pagesize'=>$this->getPdfPages($path));
	}
	/**
	 * 获取pdf页数 
	 * @param string $path	需要查看页数的pdf文件路径
	 * @return array		返回真值和pdf页数
	 */
	function getPdfPages($path){
		if(!file_exists($path)) return array(false,"文件\"{$path}\"不存在！");
		if(!is_readable($path)) return array(false,"文件\"{$path}\"不可读！");
		// 打开文件
		$fp=@fopen($path,"r");
		if (!$fp) {
			return array(false,"打开文件\"{$path}\"失败");
		}else {
			$max=0;
			while(!feof($fp)) {
				$line = fgets($fp,255);
				if (preg_match('/\/Count [0-9]+/', $line, $matches)){
					preg_match('/[0-9]+/',$matches[0], $matches2);
					if ($max<$matches2[0]) $max=$matches2[0];
				}
			}
			fclose($fp);
			// 返回页数
			return array(true,$max);
		}
	}
	/**
	 * 单个图片裁剪
	 */
	public function editImage($fileName,$savename,$width,$height,$x,$y) {
		$image = new \Think\Image();
		$image->open($fileName);
		//将图片裁剪为1600*1200并保存为corp.jpg
		$image->crop($width,$height,$x,$y)->save($savename);
	}
	/**
	 * 单个图片缩略
	 */
	public function thumbImage($fileName,$savename,$width,$height){
		$image = new \Think\Image();
		$image->open($fileName);
		$image->thumb($width, $height)->save($savename);
	}
	/**
	 * 修改一个图片 让其翻转指定度数
	 *
	 * @param string  $filename 文件名（包括文件路径）
	 * @param  float $degrees 旋转度数
	 * @return boolean
	 * @author zhaocj
	 */
	function  flip($filename,$src,$degrees = 90)
	{
		//读取图片
		$data = @getimagesize($filename);
		if($data==false)return false;
		//读取旧图片
		switch ($data[2]) {
			case 1:
				$src_f = imagecreatefromgif($filename);break;
			case 2:
				$src_f = imagecreatefromjpeg($filename);break;
			case 3:
				$src_f = imagecreatefrompng($filename);break;
		}
		if($src_f=="")return false;
		$rotate = @imagerotate($src_f, $degrees,0);
		if(!imagejpeg($rotate,$src,100))return false;
		@imagedestroy($rotate);
		return true;
	}
	
	
}
