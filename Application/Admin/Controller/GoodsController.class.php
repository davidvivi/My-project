<?php

namespace Admin\Controller;

use Think\Controller;

class GoodsController extends CommonController
{
	public function index(){
		$model = M();
		$goods = M('goods');
		$count = $goods->count();
		$Page = new \Think\Page($count,3);
		$show = $Page->show();
        $list = $model->field('a.id,goodname,typeid,price,store,view,buy,state,discribe,addtime,picname')
        ->where('a.id=ud.gid')
		->order('addtime')->limit($Page->firstRow.','.$Page->listRows)
        //可以使用table()指明你要查询的表
        ->table( array('think_goods'=>'a','think_goods_pic'=>'ud') )
        ->select();
		
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display('Goods/index');
	}
	
	public function add(){
		
		$sort = M('category');
		/*
		if(IS_POST){
			$val = I('val');
			$catelist_2= $sort->field('id,name')->where('pid='.$val)->select();
			$this->assign('catelist_2',$catelist_2);
			
			$this->ajaxReturn('1');
		}
		*/
		//一级分类
		$catelist_1 = $sort->field('id,name')->where('pid=0')->select();
		
		//二级分类
		$pid = [];
		foreach($catelist_1 as $key =>$val){
			
			$pid[] = $val['id'];
		}
		$map['pid'] = array('IN',$pid);
		$catelist_2 = $sort->field('id,name')->where($map)->select();
		
		//三级分类
		$data = [];
		foreach($catelist_2 as $key =>$val){
			
			$da[] = $val['id'];
		}
		$data['pid'] = array('IN',$da);
		$catelist_3 = $sort->field('id,name')->where($data)->select();
		
		$this->assign('catelist_1',$catelist_1);
		$this->assign('catelist_2',$catelist_2);
		$this->assign('catelist_3',$catelist_3);
		$this->display('goods/product-add');
		
		
	}
	
	public function goodsAdd(){
		if(IS_POST){
			$name = I('name');
			$price = I('price');
			$keyword = I('keyword');
			$desc = I('desc');
			$cate = I('cate_1').','.I('cate_2').','.I('cate_3').',';
			$store = I('store');
			$buy = I('buy');
			$view = I('view');
			
			$map['goodname'] = $name;
			$map['price'] = $price;
			$map['keyword'] = $keyword;
			$map['discribe'] = $desc;
			$map['typeid'] = $cate;
			$map['state'] = 1;
			$map['store'] = $store;
			$map['addtime'] = time();
			$map['buy'] = $buy;
			$map['view'] = $view;
			//dump($map);
			$data = M('goods')->add($map);
			
			//$gid = M('goods')->where('goodname='.$name)->getField('id');
			if($data){
				$this->ajaxReturn('1');
				
			}
		}
		
	}

	public function goodsDelete(){
		if(IS_POST){
			$id = I('id');
			$da = M('goods')->where('id='.$id)->delete();
			$db = M('goods_pic')->where('gid='.$id)->delete();
			
			if($da && $db){
				
				$this->ajaxReturn('1');
			}else{
				$this->ajaxReturn('0');
			}
		}
		
	}
	
	public function goodsStop(){
		if(IS_POST){
			$id = I('id');
			$map['state'] = '0';
			$da = M('goods')->where('id='.$id)->save($map);
			if($da){
				$this->ajaxReturn('1');
				
			}else{
				$this->ajaxReturn('0');
			}
		}
		
	}

	public function picAdd(){
		
		$this->display('goods/pic-add');
	}
	
	public function webuploader() {
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =      './Public/Uploads/'; // 设置附件上传根目录
		$upload->savePath  =      ''; // 设置附件上传（子）目录
		$upload->autoSub = false;  // 关闭子目录

		// 上传文件
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}else{// 上传成功 获取上传文件信息
			$pathArr = array();
			foreach($info as $file){
				array_push($pathArr, "Public/Uploads/".$file['savepath'].$file['savename']);
			}
			echo json_encode($pathArr);
		}
	}
}
