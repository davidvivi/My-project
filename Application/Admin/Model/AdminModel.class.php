<?php

namespace Admin\Model;

use Think\Model;

// 模型的名字与表名要一致
class AdminModel extends Model
{

    /**
     * [auth 验证用户是否登录]
     * @author krisz
     * @param string $name 用户名
     * @param string $password 用户输入的密码
     * @return boolean 成功返回true，否则false
     */
    public function auth($name, $password)
    {

        //通过数组的方式给参数
        $map['name'] = $name;

        //先查pwd
        $res = $this->field('password')->where($map)->find();
        // echo $this->getLastSql();

        //验证密码是否正确，返回boolean
        $bool = password_verify($password, $res['password']);

        return $bool;
    }

}