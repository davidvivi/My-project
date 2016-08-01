<?php

namespace Home\Controller;

use Think\Controller;

class CartController extends CommonController {
    
   public function index(){
	   //进入购物车,加载该用户购物车数据库
	   $id = $_SESSION['user']['id'];
	   $detail = M('user_detail');
	   
	   $detail->where('uid='.$id)->field('grade')->find();
	   switch($detail){
		   case 1:
				//没有折扣
				$zhekou = 1;
				break;
				
			case 2:
				$zhekou = 0.95;
				break;
			case 3:
				$zhekou = 0.9;
				break;
	   }
	   
	   $list = M('cart')->where('user_id='.$id)->field('goods_name,goods_price,goods_num,goods_id')->select();
	
	   $count = count($list);
	   for($i=0;$i<$count;$i++){
		   $gid = $list[$i]['goods_id'];
		   $data = M('goods_pic')->where('gid='.$gid)->field('picname')->limit(1)->find();
		   $d = M('goods')->where('id='.$gid)->field('store')->find();
		   $list[$i]['store'] = $d['store'];
		   $list[$i]['picname'] = $data['picname'];
	   }
	   $cart = M('cart');
	   $da = $cart->where('user_id='.$id)->field('goods_price,goods_num')->select();
		$total = 0;
		foreach($da as $key =>$val ){
			$price = $val['goods_price'];
			$num = $val['goods_num'];
			$total += $price * $num;
		}
	   $this->assign('total',$total);
	   $this->assign('zhekou',$zhekou);
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
			$uid = $_SESSION['user']['id'];
			$data['goods_num'] = $num;
			$cart = M('cart');
			$map['user_id']=$uid;
			$map['goods_id'] = $gid;
			$cart->where($map)->save($data);
			
			$this->ajaxReturn('1');
	   
   }
	
	//删除一个cart
   public function del(){
	   $goods_id = I('post.gid');
	   $user_id = $_SESSION['user']['id'];
	   $map['goods_id'] = $goods_id;
	   $map['user_id'] = $user_id;
	   M('cart')->where($map)->delete();
	   $this->ajaxReturn('1');
   }
   
   //批量删除
   public function delMore(){
	   
	   
   }
   
   public function cart2(){
	   $id = $_SESSION['user']['id'];
	   
	   $data = M('cart')->where('user_id='.$id)->field('goods_name,goods_price,goods_num')->select();
	   $total = I('get.total');
	   $zhekou = I('get.zhekou');
	   $this->assign('total',$total);
	   $this->assign('zhekou',$zhekou);
	   $this->assign('list',$data);
	   $this->display('cart/cart2');
   }
   
    /*
     * ajax 获取用户收货地址 用于购物车确认订单页面
     */
    public function ajaxAddress(){                               
        $address_list = M('address')->where("uid =".$_SESSION['user']['id'])->select();
        
        $c = M('address')->where("uid = {$_SESSION['user']['id']} and is_default = 1")->count(); // 看看有没默认收货地址        
        if((count($address_list) > 0) && ($c == 0)) // 如果没有设置默认收货地址, 则第一条设置为默认收货地址
            $address_list[0]['is_default'] = 1;
                     
        $this->assign('address_list', $address_list);
        $this->display('ajax_address');
    }
}
