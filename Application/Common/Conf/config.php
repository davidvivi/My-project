<?php
return array(

    //'配置项'=>'配置值'
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '192.168.11.149', // 服务器地址
    'DB_NAME'               =>  'QAQ',          // 数据库名
    'DB_USER'               =>  'wei',      // 用户名
    'DB_PWD'                =>  '123',          // 密码
    'DB_PORT'               =>  '',        // 端口
    'DB_PREFIX'             =>  'think_',    // 数据库表前缀
	//kaiqi trace 
	'SHOW_PAGE_TRACE' =>true, 

    //Auth配置
    'AUTH_CONFIG' => array(
/*        // 用户组数据表名
        'AUTH_GROUP' => 'think_auth_group',
        // 用户-用户组关系表
        'AUTH_GROUP_ACCESS' => 'think_auth_group_access',
        // 权限规则表
        'AUTH_RULE' => 'think_auth_rule',*/
        // 用户信息表
        'AUTH_USER' => 'user',

    ),
    ),
);
