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
        //根据用户名查到等级
        $list=D('user_detail')->field('grade')->where("name='$name'")->find();
        $grade = $list['grade'];
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();

        $this->assign('list',$list);
        $this->assign('data',$data);
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


 
    /**
        个人中心地址管理
    */
    /*
     *  用户地址添加页面
     */
    public function add_address()
    {
        $uid = $_SESSION['user']['id'];
        $this->assign('uid',$uid);
        $this->display('user/add_address');
    }

     /*
     *  用户地址编辑页面
     */
    public function edit()
    {   
        $id = I('get.id');
        $uid = I('get.uid');
        $data = M('address')->field('id,uid,address,name,tel,postcode')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->assign('uid',$uid);
        $this->assign('id',$id);               
        $this->display('user/edit_address');
    }

    /*
     *  用户地址编辑,添加处理
     */
    public function addressEditForm()
    {  
        //接收用户编辑时的id
        $id = I('id');
        $uid = I('uid');
        $consignee = I('consignee');
        $postcode = I('postcode');
        $tel = I('tel');
        $address = I('address');

        $data['uid']=$uid;
        $data['postcode'] = $postcode;
        $data['name'] = $consignee;
        $data['tel'] = $tel;
        $data['address'] = $address;        
        //如果有id,则是编辑否则是添加
        if($id){
            $res = M('address')->where('id='.$id)->save($data);
        }else{    
            $res = M('address')->add($data);
        }
                
        if($res){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }

    }

    /*
     *  用户地址表列
     */
    public function address_list()
    {   
        //根据session里面存的uid，查询address表；
        $uid = $_SESSION['user']['id'];
        $data = M('address')->field('id,uid,address,name,tel,postcode')->where('uid='.$uid)->select();
        //dump($data);
        //exit;
        $this->assign('uid',$uid);
        $this->assign('data',$data);
        $this->display();
    }

    /*
     *  用户地址删除处理
     */
    public function del_address()
    {   
        $id = I('id');
        if($id){ 
            $res = M('address')->where('id='.$id)->delete();
            if($res){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }      
    }
    


    /**
        安全设置管理
    */
    public function safety_settings()
    {   
        $name = $_SESSION['user']['name'];
            
        //根据用户名查到手机号
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();

        $this->assign('name',$name);
        $this->assign('data',$data);
        $this->display('');

    }

    public function password()
    {   
        $name = $_SESSION['user']['name'];
        //根据name拿到自己的密码
        $data = M('user')->field('password')->where("username='$name'")->find();
        
        $pwd = $data['password'];
        //dump($pwd);
        //exit;
        $this->assign('pwd',$pwd);
        $this->assign('name',$name);
        $this->display();
    }

    /*
     *  用户地址编辑,添加处理
     */
    public function passwordEditForm()
    {  
        
        //得到的是真正的密码
        $pwd = I('pwd');
        $name = I('username');
        $bool = password_verify(I('old_password'),$pwd);

        $new_password = I('new_password');
        $confirm_password = I('confirm_password');
        
        if($new_password != $confirm_password){
            $this->ajaxReturn('0');
        }else{  
            $this->ajaxReturn('1');
        }
    
        /*
        if($bool){  
            
            if($new_password != $confirm_password){
            $this->ajaxReturn('0');
        }else{  
            $this->ajaxReturn('1');
        }
            if($new_password == $confirm_password){
                $data['password'] = $new_password;
                $res = M('user')->where('username='.$name)->save($data);
                if($res){
                    $this->ajaxReturn('1');
                }
            }
        }else{  
            $this->ajaxReturn('0');
        }
        */
    }

}