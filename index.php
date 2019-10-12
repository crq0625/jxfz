<?php
/**
 * +-------------------------------
 * thinkphp开发的CMS系统
 * +-------------------------------
 * @version:1.0
 * 技术交流群：248846929
 * +-------------------------------
 * @author:jack<759498475@qq.com><www.ihomely.com>
 */
/* PHP版本判断 */
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

/* 开启调试模式 */
define("APP_DEBUG", TRUE);

/* 项目名称 */
define('APP_NAME', 'MenggeCMS');

/* 定义根目录 */
define('CMS_ROOT', getcwd());

/* 定义项目路径 */
define('APP_PATH', CMS_ROOT . '/' . APP_NAME . '/');


//网站当前路径
define('SITE_PATH', dirname(__FILE__)."/");


/* 检测系统是否需要安装 */
if (!file_exists(APP_PATH."Install/Data/install.lock")) {
	$_GET['m']='install';
}

//载入框架核心文件
require './ThinkPHP/ThinkPHP.php';
