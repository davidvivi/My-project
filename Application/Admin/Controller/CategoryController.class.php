<?php

namespace Admin\Controller;

use Think\Controller;

class CategoryController extends CommonController {

    public function index()
    {   


        $category = M('category');
        $count = $category->count();   
        $Page = new \Think\Page($count,8);
        $show = $Page->show();
        $categoryclass = new \Admin\Model\CategoryModel();
        $categorydata = $categoryclass->categoryInfo($Page->firstRow,$Page->listRows);
        $category = $categoryclass->cateSwitch($categorydata);
        $category = $categoryclass->pidSwitch($category);
        //$categorydata = $categoryclass->userSwitch($categorydata);
        $this->assign('count', $count);
        $this->assign('page', $show);
        $this->assign('category',$category);
    
        $this->display('category/category-list');
    }

    public function cateEdit()
    { 
        $id = I('id');       
        $category = M('category');
        $name = $category->where("id='{$id}'")->getField('name');       
        $father = I('father');              
        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->assign('father',$father);
        $this->display('category/category-edit');
        
    }
    public function cateEditForm()
    { 
        $id = I('id');
        $name = I('name');
        if($id){ 
            $category = M('category');
            $dd = $category->where("name='{$name}'")->find();
            if($dd){ 
                $his->ajaxReturn('0');
            }else{
                $data['name'] = $name;
                $du = $category->where("id='{$id}'")->save($data);
                $this->ajaxReturn('1');
            }
        }else{ 
            $this->ajaxReturn('2');
        }
    }  
    
    public function cateDelete()
    { 
        $id = I('id');        
        $category = M('category');
        if($id){
            // 如果查询有结果，则表明次id下还有子分类
            $ds = $category->field('id')->where("pid='{$id}'")->find();
            if($ds){ 
                $this->ajaxReturn('2');
            }else{ 
                // 没有子分类在这执行
                // 如果是第三级分类，还得把相应的图片下架。
                $categoryclass = new \Admin\Model\CategoryModel();
                $goodspic = $categoryclass->picState($id);
                $du = $category->where("id='{$id}'")->delete();

                if($du){ 
                    $this->ajaxReturn('1');
                }else{ 
                    $this->ajaxReturn('0');
                }
            }     
        }else{ 
            $this->ajaxReturn('0');
        }

    }
    public function cateAdd()
    { 
        $id = I('id');
        // id 存在就添加子类，否则添加一级分类
        if($id){
            $category = M('category');
            $data = $category->field('id,pid,name,path')->where("id='{$id}'")->find();
            $num = substr_count($data['path'],',');
            $path = $data['path'].$id.',';
            $this->assign('path',$path);
            $this->assign('id',$id);
            $this->display('category/category-sonadd');
        }else{ 
            $this->display('category/category-firstadd');   
        }
    }
    public function cateFirstAdd()
    { 
        $pid = I('pid');
        $name = I('name');
        $path = I('path');
        if($name){ 
            $category = M('category');
            $dd = $category->where("name='{$name}'")->find();
            if($dd){ 
                $this->ajaxReturn('2');
            }else{
                $data['name'] = $name;
                $data['pid'] = $pid;
                $data['path'] = $path;
                $du = $category->add($data);
                $this->ajaxReturn('1');
            }
        }else{ 
            $this->ajaxReturn('0');
        }
    }  


    /*
    public function cateSearch()
    { 
        $data = I('text');
        $sid = I('id'); //  区别是哪个页面
        
        $cate = M('cate');
        $wherecate['catename'] = array('like',"%{$data}%");

        $wherecate['status'] = $sid;


        $catedata = $cate->field('id,catename,addtime,tel,status')->where($wherecate)->find();
        if(!$catedata)
        { 
            $wheretel['tel'] = array('like',"%{$data}%");
            $wheretel['status'] = $sid;
            $catedata = $cate->field('id,catename,addtime,tel,status')->where($wheretel)->find();
        }
        
        if($catedata){ 
            $this->ajaxReturn($catedata);
        }else{ 
            $this->ajaxReturn('失败');
        }
    }

    public function cateFind(){ 

        $text = I('text'); // 搜索框里的内容
        $cate = M('cate');

        $sid = I('id');

        //$catedetail = M('cate_detail');
        $wherecate['catename'] = array('like',"%{$text}%");
        $wherecate['status'] = $sid;
        $wheretel['tel'] = array('like',"%{$text}%");
        $wheretel['status'] = $sid;

        $countcate = $cate->where($wherecate)->count();
        $counttel = $cate->where($wheretel)->count();
        $count = $countcate + $counttel;
        // $Page = new \Think\Page($count,5);
        // $show = $Page->show();



        $catecate = $cate->field('id,catename,addtime,tel,status')->where($wherecate)->select();
        $catetel = $cate->field('id,catename,addtime,tel,status')->where($wheretel)->select();
        $catedata = array_merge($catecate,$catetel);
        //dump($catedata);exit;   
        $cateclass = new \Admin\Model\UserModel();
        $catedata = $cateclass->cateInfo($catedata);
        $catedata = $cateclass->cateSwitch($catedata);
        $this->assign('count', $count);
        $this->assign('act', 1);
        $this->assign('catedata',$catedata);
        //dump($catedata);exit;
        if($sid == 1){
            $this->display('cate/cate-list');
        }else{ 
            $this->display('cate/cate-del');
        }
    }
    */
    
}