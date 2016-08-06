<?php

namespace Home\Controller;

use Think\Controller;

class CategoryController extends Controller 
{
    public function index(){
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $this->assign('name',$name);
        }
        $category = M('category');
        // 得到一级分类
        $firstcate = $category->field('id,name')->where('pid=0')->select();
        foreach($firstcate as $key => $val){ 
            $pidtwo = $val['id']; 
            // 得到二级分类
            $data = $category->field('id,pid,name')->where("pid='{$pidtwo}'")->select();
            $secondcate[] = $data; 
            foreach($data as $k => $v){ 
                $pidthird = $v['id'];
                //得到三级分类
                $thirddata = $category->field('id,pid,name')->where("pid='{$pidthird}'")->select();
                // 把三级分类放到二级的son里
                $secondcate[$key][$k]['son'] = $thirddata;
            }
        }

        // 友情链接
        $link = M('link');
        $datalink['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($datalink)->select();

        
        // 假设从其他页面传来的ID
        $getid = I('id');
        // 如果没有id传过来，则赋予id 值1
        if(!$getid){ 
            $getid = 1;
        }
        // 到model里处理
        $categoryclass = new \Home\Model\CategoryModel();
        $precate = $categoryclass->idHandle($getid);
        $goodsdata = $categoryclass->hostShop();
        //dump($goodsdata);

        $id = $precate['firstid']; // 一级分类id
        
        
        // 根据一级id得到一级分类
        
        foreach($firstcate as $k1 => $v1){ 
            if($v1['id'] == $id){ 
                $firstname = $v1['name'];
                $firstid = $id;
            }
        }
        // 得到二级分类
        $secondca = $category->field('id,pid,name')->where("pid='{$id}'")->select();
        //dump($secondca);
        foreach($secondca as $k2 => $v2){
            $secondid = $v2['id'];
            $thirdca = $category->field('id,pid,name')->where("pid='{$secondid}'")->select();
            $secondca[$k2]['third'] = $thirdca;
        }
    

        // 商品的遍历
        $type = I('type');
        if(!$type){
            $type = 'id';
        }
        
        $return = $categoryclass->goodsShop($getid,$type);
        // 商品数据
        $waresdata = $return[0];
        // 分页
        $page = $return[1];
        // $yan = I('yan');
        // if($yan){
        //     dump($waresdata);
        //     $this->ajaxReturn('1');
        // }
        
        //dump($waresdata);

        $this->assign('link',$link_list);
        $this->assign('getid',$getid);
        $this->assign('firstcate',$firstcate);
        $this->assign('secondcate',$secondcate);
        $this->assign('firstname',$firstname);
        $this->assign('firstid',$firstid);
        $this->assign('secondca',$secondca);
        $this->assign('precate',$precate);
        $this->assign('goodsdata',$goodsdata);
        $this->assign('waresdata',$waresdata);
        $this->assign('page',$page);
        $this->display('category/category');
        
    }

    public function cateSearch()
    {

    	if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $this->assign('name',$name);
        }
        $category = M('category');
        // 得到一级分类
        $firstcate = $category->field('id,name')->where('pid=0')->select();
        foreach($firstcate as $key => $val){ 
            $pidtwo = $val['id']; 
            // 得到二级分类
            $data = $category->field('id,pid,name')->where("pid='{$pidtwo}'")->select();
            $secondcate[] = $data; 
            foreach($data as $k => $v){ 
                $pidthird = $v['id'];
                //得到三级分类
                $thirddata = $category->field('id,pid,name')->where("pid='{$pidthird}'")->select();
                // 把三级分类放到二级的son里
                $secondcate[$key][$k]['son'] = $thirddata;
            }
        }

        // 友情链接
        $link = M('link');
        $datalink['state'] = array('GT',0);
        $link_list = $link ->field('contents,url')->where($datalink)->select();

        // 判断是否有search传过来，以及session里有没有值
    	$search = I('search');
    	if($search){
	    	if($_SESSION['catesearch']){
	    		if($search == $_SESSION['catesearch']){
	    			
	    		}else{
	    			$_SESSION['catesearch'] = $search;
	    		}
	    	}else{
	    		$_SESSION['catesearch'] = $search;
	    	}
        }else{
        	$search = $_SESSION['catesearch'];
        }

        $categoryclass = new \Home\Model\CategoryModel();
        $goodsdata = $categoryclass->hostShop();
        $goodssearch = $categoryclass->catesearch($search);
        $waresdata = $goodssearch['data'];
        $page = $goodssearch['page'];
        $this->assign('firstcate',$firstcate);
        $this->assign('secondcate',$secondcate);
        $this->assign('link',$link_list);
        $this->assign('goodsdata',$goodsdata);
       	$this->assign('waresdata',$waresdata);
        $this->assign('page',$page);
        $this->display('category/category');

    }
    
}