<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller 
{
	public function index(){
			$this->display('index/login');
		
	}
	
    public function checkLogin(){
        

        $username = I('post.username');
        //查找id
        $data= M('user')->field('id')->where(array('username'=>$username))->find();
        
        $id = $data['id'];
        //查找密码
		$password=M('user')->field('password')->where(array('username'=>$username))->find();
	    

	   //密码加盐处理
		$bool = password_verify(I('post.password'),$password['password']);



		if(!$bool){ 
			$this->error('登陆失败');
		}else{  
            $_SESSION['user']['id']=$id;
			$_SESSION['user']['name']=$username;
			$this->success('登陆成功',U('index/index'),2);
		}
    }

         


    
}