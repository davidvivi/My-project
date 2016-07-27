<?php

namespace Admin\Controller;

use Think\Controller;

class ManagerController extends CommonController {
    // ����Ա�б�  status 0������ã�1����ʹ����
    public function index()
    {   
        $admin = M('admin');
        $count = $admin->count();
        $Page = new \Think\Page($count,8);
        $show = $Page->show();
   
        $data = $admin->field('id,name,addtime,tel,email,status')->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($data);
		//ͨ��uid��ȡ�û���group_id ��ͨ��gid��ȡ�û���
		
		foreach($data as $key =>$val){
			$uid = $val['id'];
			//dump($uid);
			$gid = M('auth_group_access')->where('uid='.$uid)->getField('group_id');
			//dump($gid);
			$title = M('auth_group')->where('id='.$gid)->getField('title');
			//dump($title);
			$data[$key]['title'] = $title;
			
		}

	    //��ʽ��ʱ���
		foreach($data as $key=>$value){
			$data[$key]['addtime'] = date("Y-m-d H:i:s",$data[$key]['addtime']);		
		}
	   
        $this->assign('count', $count);
        $this->assign('page', $show);
        
        $this->assign('data',$data);
        $this->display('manager/admin-list');
        
    }
	
	public function adminAddForm()
    { 
		dump('111');
        $name = I('name');
        $password = password_hash(I('password'),PASSWORD_DEFAULT);
        $tel = I('tel');
		$email = I('email');
        $addtime = time();
		$group = I('group');
		
        $admin = M('admin');   
              
        $data['name'] = $name;
        $data['password'] = $password;
        $data['tel'] = $tel;
		$data['email'] = $email;
		$data['addtime'] = $addtime;
		$data['status'] = '1';
		
		$du = $admin->add($data);
		$uid = $admin->where('name='.$name)->getField('id');
		dump($uid);
		$dataa['uid'] = $uid;
		$dataa['group_id'] = $group;		
		$access = M('auth_group_access');
		$da = $access->add($dataa);
		
		
        
        if($du && $da){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
	
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
    
    public function adminDelete()
    { 
        $id = I('id');
        $user = M('user');
        $userdetail = M('user_detail');
        if($id){ 
            $du = $user->where("id='{$id}'")->delete();
            $dd = $userdetail->where("uid='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
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
    public function userEdit()
    { 
        $id = I('id');
        $user = M('user');
        $userdetail = M('user_detail');
        $userdata = $user->field('id,username,addtime,tel,status')->where("status=1 AND id='{$id}'")->select();
       
        $userid = $userdata[0]['id'];              
        $detaildata = $userdetail->field('sex,email,address,grade')->where("uid='{$userid}'")->select();
        
        $data1 = array_pop($userdata);
        $data2 = array_pop($detaildata);
        $data = array_merge($data1,$data2);
        
       

        $this->assign('act',1);
        $this->assign('formdata',$data);
        $this->display('user/user-edit');
        
    }
    public function userEditForm()
    { 
        $id = I('id');
        $grade = I('grade');
        if($id){ 
            $userdetail = M('user_detail');
            $detaildata = $userdetail->field('grade')->where("uid='{$id}'")->select();
            $data['grade'] = $grade;
            $du = $userdetail->where("uid='{$id}'")->save($data);
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }  
    public function userSearch()
    { 
        $data = I('text');
        $sid = I('id'); //  �������ĸ�ҳ��
        
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
            $this->ajaxReturn('ʧ��');
        }
    }

    public function userFind(){ 

        $text = I('text'); // �������������
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
    
}