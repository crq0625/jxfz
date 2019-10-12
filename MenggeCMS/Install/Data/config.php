<?php
/**
 * +-------------------------------
 * thinkphp开发的CMS系统
 * +-------------------------------
 * @version:1.0
 * 技术交流群：248846929
 * +-------------------------------
 * @author:jack<759498475@qq.com><www.ihomely.com>
 * 系统配置文件
 */
return array(
		'DB_TYPE'			 => 'mysql',
		'DB_HOST'			 => '#DB_HOST#',
		'DB_NAME'			 => '#DB_NAME#',
		'DB_USER'			 => '#DB_USER#',
		'DB_PWD'			 => '#DB_PWD#',
		'DB_PORT'			 => '#DB_PORT#',
		'DB_PREFIX'			 => '#DB_PREFIX#',
		
		'TMPL_PARSE_STRING' => array(
				'__STATIC__' => __ROOT__ . '/Static',
		),
		'URL_MODEL'=>1,
);