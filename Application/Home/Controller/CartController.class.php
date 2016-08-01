<?php

namespace Home\Controller;

use Think\Controller;

class CartController extends CommonController {
    
   public function index(){
	   
	   //进入购物车,加载该用户购物车数据库
	   $id = $_SESSION['user']['id'];
	   
	   $list = M('cart')->where('user_id='.$id)->field('goods_name,goods_price,goods_num,goods_id')->select();
	
	   $count = count($list);
	   for($i=0;$i<$count;$i++){
		   $gid = $list[$i]['goods_id'];
		   $data = M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
		   $d = M('goods')->where('id='.$gid)->field('store')->find();
		   $list[$i]['store'] = $d['store'];
		   $list[$i]['picname'] = $data['picname'];
	   }
	   
	   $this->assign('count',$count);
	   $this->assign('list',$list);
	   $this->display('cart/index');
   }
   
   //商品添加到购物车
   public function add(){
	   $map['goods_id'] = I('post.id');
	   $map['user_id'] = $_SESSION['user']['id'];
	   $map['goods_num'] = I('post.num');
	   
	   $data = M('goods')->field('goodname,price')->where('id='.$goods_id)->select();
	   $map['goods_name']->$data[0]['goodname'];
	   $map['goods_price']->$data[0]['price'];
	   $map['addtime'] = time();
	   
	   M('cart')->add($map);
	   
   }
   
   public function logout(){
	   
	   unset($_SESSION['user']);
	   $this->ajaxReturn('1');
	   
   }
   
   public function act(){
			$num = I('no');
			$gid = I('id');
			$data['goods_num'] = $num;
			$da = M('cart')->where('goods_id='.$gid)->save($data);
			$this->ajaxReturn('1');
	   
   }

   public function del(){
	   
	   
	   M('cart')->where()->delete();
	   
   }
}
