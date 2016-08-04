<?php

namespace Admin\Controller;

use Think\Controller;

class ManagerController extends CommonController {
    // 管理员列表  status 0代表禁用，1代表使用中
    public function index()
    {   
        $admin = M('admin');
        $count = $admin->count();
        $Page = new \Think\Page($count,8);
        $show = $Page->show();
   
        $data = $admin->field('id,name,addtime,tel,email,status')->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($data);
		//通过uid获取用户组group_id 再通过gid获取用户组
		
		foreach($data as $key =>$val){
			$uid = $val['id'];
			//dump($uid);
			$gid = M('auth_group_access')->where('uid='.$uid)->getField('group_id');
			//dump($gid);
			$title = M('auth_group')->where('id='.$gid)->getField('title');
			//dump($title);
			$data[$key]['title'] = $title;
			
		}

	    //格式化时间戳
		foreach($data as $key=>$value){
			$data[$key]['addtime'] = date("Y-m-d H:i:s",$data[$key]['addtime']);		
		}
	   
        $this->assign('count', $count);
        $this->assign('page', $show);
        
        $this->assign('data',$data);
        $this->display('manager/admin-list');
        
    }
	
	/*=====管理员添加开始========*/
	public function add(){
		
		$this->display('manager/admin-add');
		
	}
	
	public function adminAddForm()
    { 
		$admin = M('admin');
		$access = M('auth_group_access');
		
        $name = I('name');
        $password = password_hash(I('password'),PASSWORD_DEFAULT);
        $tel = I('tel');
		$email = I('email');
        $addtime = time();
		$group = I('group');
              
        $data['name'] = $name;
        $data['password'] = $password;
        $data['tel'] = $tel;
		$data['email'] = $email;
		$data['addtime'] = $addtime;
		$data['status'] = '1';
		
		$du = $admin->add($data);
		
		$uid = $admin->where('name='.$name)->getField('id');
		
		$map['uid'] = $uid;
		$map['group_id'] = $group;
		
		$da = $access->add($map);
		
		
        
        if($da){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
	
	/*======管理员添加结束=======*/
	
	/*=====用户组管理======*/
	public function userGroup(){
		$group = M('auth_group');
		$count = $group->count();
		$Page = new \Think\Page($count,8);
        $show = $Page->show();
   
        
		$data = $group->field('id,title,des,status')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->assign('data',$data);
		$this->display('manager/admin-group');
	}
    
	/*=======管理员删除======*/
    public function adminDelete()
    { 
			
			$id = I('id');
			$admin = M('admin');
			$access = M('auth_group_access');
			if($id){ 
				$du = $admin->where('id='.$id)->delete();
				$dd = $access->where('uid='.$id)->delete();
				if($du && $dd){ 
					$this->ajaxReturn('1');
				}else{ 
					$this->ajaxReturn('0');
				}
			}else{ 
				$this->ajaxReturn('0');
			}

    }
	
	/*
    public function userReturn()
    { 
        
        $id = I('id');
        $i = I('i');

        $user = M('user');
        if($id){ 
            if($i == 1){ 
                $data['status'] = '0';
            }else{
                $data['status'] = '1';
            }
            $du = $user->where("id='{$id}'")->save($data);
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }
        
    }
	*/
	
	/*=====管理员信息编辑开始=====*/
	public function edit(){
		$id = I('id');
		$admin = M('admin');
		
		$list = $admin->where('id='.$id)->field('name,tel,email')->select();
		//dump($list);exit;
		$name = $list[0]['name'];
		$tel = $list[0]['tel'];
		$email = $list[0]['email'];
		//dump($tel);exit;
		$this->assign('id',$id);
		$this->assign('name',$name);
		$this->assign('tel',$tel);
		$this->assign('email',$email);
		$this->display('manager/admin-edit');
	}
	
	/*
    public function adminEdit()
    { 
        $id = I('id');
        $admin = M('admin');
        $access = M('auth_group_access');
        $data = $admin->field('id,name,tel,email,status')->where("id='{$id}'")->select();
       
        $userid = $userdata[0]['id'];              
        $detaildata = $userdetail->field('sex,email,address,grade')->where("uid='{$userid}'")->select();
        
        $data1 = array_pop($userdata);
        $data2 = array_pop($detaildata);
        $data = array_merge($data1,$data2);
        
       

        $this->assign('act',1);
        $this->assign('formdata',$data);
        $this->display('user/user-edit');
        
    }
    */
	public function adminEditForm()
    { 
		//$this->display('manager/text');
		
        $admin = M('admin');
		$access = M('auth_group_access');
		
		$id = I('id');
		//dump($id);exit;
        $name = I('name');
        $password = password_hash(I('password'),PASSWORD_DEFAULT);
        $tel = I('tel');
		$email = I('email');
        $addtime = time();
		$group = I('group');
		
		$data['name'] = $name;
        $data['password'] = $password;
        $data['tel'] = $tel;
		$data['email'] = $email;
		$data['addtime'] = $addtime;
		$data['status'] = '1';
		$da = $admin->where('id='.$id)->save($data);
		
		$map['group_id'] = $group;
		
		$dd = $access->where('uid='.$id)->save($map);
		
		if($dd && $da){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
		
    }  
	
	/*=======管理员信息编辑结束=======*/
	
    public function userSearch()
    { 
        $data = I('text');
        $sid = I('id'); //  区别是哪个页面
        
        $user = M('user');
        $whereuser['username'] = array('like',"%{$data}%");

        $whereuser['status'] = $sid;


        $userdata = $user->field('id,username,addtime,tel,status')->where($whereuser)->find();
        if(!$userdata)
        { 
            $wheretel['tel'] = array('like',"%{$data}%");
            $wheretel['status'] = $sid;
            $userdata = $user->field('id,username,addtime,tel,status')->where($wheretel)->find();
        }
        
        if($userdata){ 
            $this->ajaxReturn($userdata);
        }else{ 
            $this->ajaxReturn('失败');
        }
    }

    public function userFind(){ 

        $text = I('text'); // 搜索框里的内容
        $user = M('user');

        $sid = I('id');

        //$userdetail = M('user_detail');
        $whereuser['username'] = array('like',"%{$text}%");
        $whereuser['status'] = $sid;
        $wheretel['tel'] = array('like',"%{$text}%");
        $wheretel['status'] = $sid;

        $countuser = $user->where($whereuser)->count();
        $counttel = $user->where($wheretel)->count();
        $count = $countuser + $counttel;
        // $Page = new \Think\Page($count,5);
        // $show = $Page->show();



        $useruser = $user->field('id,username,addtime,tel,status')->where($whereuser)->select();
        $usertel = $user->field('id,username,addtime,tel,status')->where($wheretel)->select();
        $userdata = array_merge($useruser,$usertel);
        //dump($userdata);exit;   
        $userclass = new \Admin\Model\UserModel();
        $userdata = $userclass->userInfo($userdata);
        $userdata = $userclass->userSwitch($userdata);
        $this->assign('count', $count);
        $this->assign('act', 1);
        $this->assign('userdata',$userdata);
        //dump($userdata);exit;
        if($sid == 1){
            $this->display('user/user-list');
        }else{ 
            $this->display('user/user-del');
        }
    }
    
	public function stop(){
		$id = I('id');
		$map['status'] = 0;
		M('admin')->where('id='.$id)->save($map);
		$tihs->ajaxReturn('1');
	}
	
	public function start(){
		$id = I('id');
		$map['status'] = 1;
		M('admin')->where('id='.$id)->save($map);
		$tihs->ajaxReturn('1');
	}
}