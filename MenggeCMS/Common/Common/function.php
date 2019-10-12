<?php
/**
 * 全局获取验证码图片 生成的是个HTML的img标签
 * length=4&size=20&width=238&height=50
 * length:字符长度
 * size:字体大小
 * width:生成图片宽度
 * heigh:生成图片高度
 * @param type $imgparam 图片的属性设置
 * @param type $imgattrs IMG标签
 * @return type
 */
function show_verify_img($imgparam = 'length=4&size=15&width=238&height=50', $imgattrs = 'style="cursor: pointer;" title="点击获取"') {
	$src = U('Api/Index/show_verify', $imgparam);
	return $img = <<<hello
<img onclick='this.src+="?"'  src="$src" $imgattrs/>
hello;
}
/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 */
function check_verify($code, $id = 1) {
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}
/**
 * 检测用户是否登录
 * @return Ambigous <boolean, mixed, NULL, unknown>
 */
function is_login() {
	$user = session('admin');
	return empty($user) ? false : $user;
}
/**
 * 短信发送
 * 参数：要发送的手机号，发送内容，时间
 * @param $phone
 * @param $content
 * @param $time
 */
function send_sms($phone,$content,$time=NULL){
	$content=iconv("UTF-8","GB2312",$content);
	$username=C("SEND_USER");//获取短信平台用户名
	$password=C("SEND_PASS");//获取密码
	$getmsg = "http://service.winic.org:8009/sys_port/gateway/?id=".$username."&pwd=".$password."&to=".$phone."&content=".$content."&time=".$time;
	$xhr=new COM("MSXML2.XMLHTTP");
	$xhr->open("GET",$getmsg,false);
	$xhr->send();
	$file = $xhr->responseText;
	$fund=strchr($file,'000/');//判断返回值中是否有“000/”，即判断短信是否发送成功
	if($fund){
		return true;
	}else{
		return false;
	}

}