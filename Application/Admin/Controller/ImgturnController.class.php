<?php

namespace Admin\Controller;

use Think\Controller;

class ImgturnController extends CommonController {
    
    public function index()
    {   
        $imgturn = M('imgturn');
        $count = $imgturn->count();
        $Page = new \Think\Page($count,4);
        $show = $Page->show();
        $data = $imgturn->order('state')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($data as $key =>$val){
            $data[$key]['addtime']= date('Y-m-d',$val['addtime']);
        }
        //dump($data);
        $this->assign('count', $count);
        $this->assign('page', $show);            
        $this->assign('data',$data);
        $this->display('imgturn/imgturn-list');
      
    }
    public function imgturnEdit()
    { 
        $id = I('id');
        $imgturn = M('imgturn');
        $imgturnlist = $imgturn->field('id,state')->where("id='{$id}'")->select();
        $data = array_pop($imgturnlist);
        $this->assign('formdata',$data);
        $this->display('imgturn/imgturn-edit');
    }
    public function imgturnEditForm()
    { 
        $id = I('id');
        //$url = I('url');
        //$contents = I('contents');
        $state = I('state');
        $imgturn = M('imgturn');
        // 如果之前有同样的状态存在，把它变为0
        if($state != 0){
            $imgturnlist = $imgturn->field('id')->where("state='{$state}'")->select();
           
            $imgturnid = $imgturnlist[0]['id'];
            $da['state'] = 0;
            $dl = $imgturn->where("id='{$imgturnid}'")->save($da);
        }
        $data['state'] = $state;
        // 如果有id，则是编辑否则是添加
        if($id){
            $du = $imgturn->where("id='{$id}'")->save($data);
        }else{ 
            $du = $imgturn->add($data);
        }
        if($du){
            $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    }

    public function imgturnDelete()
    { 
        $id = I('id');
        $imgturn = M('imgturn');
        if($id){ 
            $du = $imgturn->where("id='{$id}'")->delete();
            if($du){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }

    }
    
    public function add(){
        
        $this->display('imgturn/picture-add');
        
    }
    /*
    public function imgAdd()
    { 
        $imgturn = M('imgturn');
                
        $category = I('category_id');
        $imgname = I('imgname');
        $state = I('state');
        $imgurl = I('imgurl');
              
        $data['category_id'] = $category;
        $data['imgname'] = $imgname;
        $data['state'] = $state;
        $data['imgurl'] = $imgurl;
        
        $du = $imgturn->add($data);
                   
        if($du){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }
    } 
    */ 

    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize = 3145728 ;// 设置附件上传大小    
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
        $upload->rootPath = './Public/';
        $upload->savePath  = './imgturnUploads/'; // 设置附件上传目录  
        //$upload->saveName = time().'_'.mt_rand(); //设置上传文件名
        // 上传文件     
        $info = $upload->upload();
        $imgname = I('imgname');
        $state = I('state');

        //dump($imgname);
        //dump($state);

        //dump($info);
        $data['imgname']=$imgname;
        $data['state']=$state;
        $data['imgurl']=$info['photo']['savename'];
        $data['addtime'] = time();

        $res = M('imgturn')->where('state='.$state)->find();
        if(!$info) {
            // 上传错误提示错误信息        
            $this->error($upload->getError());    
        }elseif($info && !$res){
            M('imgturn')->add($data);
            $this->success('上传成功！');        
        }elseif($res){  
            $this->error('图片状态不能与已有状态重复');
        }
    }
}