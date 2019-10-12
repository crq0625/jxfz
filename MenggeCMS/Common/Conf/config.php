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
		'DB_TYPE'			 => 'mysqli',
		'DB_HOST'			 => 'localhost',
		'DB_NAME'			 => 'jxfz',
		'DB_USER'			 => 'root',
		'DB_PWD'			 => 'chenruiqiang',
		'DB_PORT'			 => '3306',
		'DB_PREFIX'			 => 'jxfz_',
		'SYSTEM_NAME'        => '促学',
		'TMPL_PARSE_STRING' => array(
				'__STATIC__' => __ROOT__ . '/Static',
		),
		'URL_MODEL'=>0,
		'SHOW_ERROR_MSG'        =>  true,//显示错误信息
		'DEFAULT_CHARSET'=>'UTF8',
		//权限验证
		'ADMIN_AUTH_KEY'=>'adm',//超级管理员
		'USER_AUTH_ON' => true,
		'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
		'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
		'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
		'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
		'USER_AUTH_GATEWAY' => 'Admin/Login/Index', // 默认认证网关
		'NOT_AUTH_MODULE' => 'Home', // 默认无需认证模块
		'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
		'GUEST_AUTH_ID' => 0, // 游客的用户ID
		'RBAC_ROLE_TABLE' =>'jxfz_sysrole',
		'RBAC_USER_TABLE' =>'jxfz_sysadminrole',
		'RBAC_ACCESS_TABLE' =>'jxfz_sysmenurole',
		'RBAC_NODE_TABLE' =>'jxfz_sysmenu',
);