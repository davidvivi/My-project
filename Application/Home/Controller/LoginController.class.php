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
        $data= M('user')->field('id')->where(array('username'=>$username))->find();
        $id = $data['id'];

		$password=M('user')->field('password')->where(array('username'=>$username))->find();
	    

	   
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