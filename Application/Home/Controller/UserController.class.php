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
     *  用户地址添加页面
     */
    public function add()
    {
        $this->display('user/add_address');
    }

     /*
     *  用户地址编辑页面
     */
    public function edit()
    {   
        $id = I('get.id');
        $data = M('address')->field('id,uid,address,name,tel,postcode')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->assign('id',$id);               
        $this->display('user/edit_address');
    }

    public function addressEditForm()
    {  
        $id = I('get.id');
        $email = I('email');
       
        $data['postcode'] = $email;        
        $res = M('address')->where('id='.$id)->save($data);
                
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
        $this->assign('data',$data);
        $this->display();
    }
    

}