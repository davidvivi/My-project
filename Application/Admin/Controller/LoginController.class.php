<?php

namespace Admin\Controller;

use Think\controller;

class LoginController extends controller
{   
    public function index()
    {   
        if(IS_POST){    
            
            $yzm = I('post.yzm');
            $verify = new \Think\Verify();

            $bool = $verify->check($yzm);

            //验证失败
            if( !$bool ){
                // $this->redirect('index');
                $this->error('验证码错误');
            }

            //判断用户名和密码是否正确
            $name = I('post.name');
            $password = I('post.password');
            
            $admin = D('Admin');

            //调用UserModel的auth方法进行登录
            $bool = $admin->auth($name, $password);

            if($bool){
                //将识别成功的用户名存进session，作为登录的依据
                $_SESSION['admin_name']=$name;
                $this->success('成功', U('Index/index'));
                }else{
                    $this->error('失败');
                }
        }else{
            $this->display('Login/index');            
        }
    }

     //验证码
    public function makeCode()
    {
        $verify = new \Think\Verify();
        $verify->length=2;
        $verify->entry();
    }
}


    


