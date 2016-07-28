<?php

namespace Home\Controller;

use Think\Controller;

class RegController extends Controller
{


    public function login()
    {

        $this->display();
    }
  
    //自动验证
    protected $_validate = array(
    );
    protected $_auto = array(

        array('password', 'myHash', 3, 'callback'),
    );
    //自动hash加密密码
    protected function myHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //自动hash加密密码


    public function reg()
    {

      $password=I('post.password');
        //$hash=password_hash($password, PASSWORD_DEFAULT);
        $hash=$this->myHash($password);
       // $hash = password_hash($password, PASSWORD_DEFAULT);
        $password2=I('post.password2');
        $hash2=$this->myHash($password2);


        //if( preg_match() )
        //添加到用户表


        $User = M("user"); 
        $data['username'] = I('post.username');
        $data['password'] = $hash;
        $data['tel']   = I('post.tel');
        $data['status'] = 1;
        $Verify = new \Think\Verify();
        $verifyCode = $Verify->check(I('post.verify'));
        
       // $data['password'] = I('post.password');
        if($password != $password2 )
            {   
                $this->error("密码不一致");
            }elseif(!$verifyCode){    
                $this->error('验证失败');
            }else{  
                $arr = $User->data($data)->add();
                }

        if($arr){

            
            //添加到用户附加表
            $addendum = M('user_detail');

            $user_Add = array(
                'uid'=>$arr,
                'name'=>$data['username'],
            );

            $addendum->add($user_Add);
            $this-> success('注册成功',U('Home/Index/login'));


        }else{
            echo "user失败";
        }
        


    }

    public function verify() {
            $Verify = new \Think\Verify(array(   
            'length' => 3,
            'useNoise' => FALSE,
            'fontSize' => 15,
            'imageW'   =>100,
            'imageH'    => 35,
            'useCurve'  =>false,
            'fontttf' => '5.ttf',
            ));
            $Verify->entry();
        }

        public function check_verify($code){    
        $verify = new \Think\Verify();  
        return $verify->check($code);
    }


}