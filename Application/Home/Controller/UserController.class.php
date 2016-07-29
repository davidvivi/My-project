<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends CommonController 
{
    public function index()
    {
        //如果session里面有user
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            
        }    
        
        //根据用户名查到等级，id
        $list=D('user_detail')->field('grade,id')->where("name='$name'")->find();
        $grade = $list['grade'];
        $id = $list['id'];
        dump($id);
        
        
        $this->assign('grade',$grade);
        $this->display('');        

    }

    /*
     *   退出
     */
    public function logout()
    {   
        session_unset();
        session_destroy();
        header("location:".U('Home/Index/index'));
        exit;
    }
 
    /*
     *  用户地址添加
     */
    public function add()
    {
        $this->display('user/edit_address');
    }

    /*
     *  用户地址表列
     */
    public function address_list()
    {   
    }

}