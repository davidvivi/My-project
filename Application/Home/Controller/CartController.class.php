<?php

namespace Home\Controller;

use Think\Controller;

class CartController extends CommonController {
    
   public function index(){
	   //进入购物车,加载该用户购物车数据库
	   $id = $_SESSION['user']['id'];
	   $detail = M('user_detail');
	   
	   //购物车推荐商品
	   $go = M('goods')->field('id,goodname,addtime')->order('view desc')->limit(10)->select();
	   foreach($go as $key =>$val){
		   $map['gid'] = $val['id'];
		   $go[$key]['addtime'] = date('Y-m-d',$go[$key]['addtime']);
		   $aa = M('goods_pic')->field('picname')->where($map)->limit(1)->select();
		   $go[$key]['picname'] = $aa[0]['picname']; 
	   }
	   
	   
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
		   $time = M('goods')->where('id='.$gid)->field('addtime')->find();
		   $d = M('goods')->where('id='.$gid)->field('store')->find();
		   $list[$i]['addtime'] = date('Y-m-d',$time['addtime']);
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
	   $this->assign('go',$go);
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
	   $goods_id = I('post.id');
	   $uid = $_SESSION['user']['id'];
	   //检查购物车中有无相同商品
	   $arr = M('cart')->where("user_id='{$uid}'")->field('goods_id')->select();
	   //$this->ajaxReturn($arr);
	   foreach($arr as $key =>$val){
		   if(in_array($goods_id,$val)){
			   $this->ajaxReturn('0');
		   }
	   }
	   $data = M('goods')->field('goodname,price')->where("id='{$goods_id}'")->select();
       $map['goods_name'] = $data[0]['goodname'];
	   $map['goods_price'] = $data[0]['price'];
	   $map['addtime'] = time();
	   M('cart')->add($map);
	   $this->ajaxReturn('1');
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
 
	 /**
     * ajax 获取订单商品价格 或者提交 订单
     */
    public function cart3(){
        
        $address_id = I("address_id"); //  收货地址id
        $shipping_code =  I("shipping_code"); //  物流种类       
        $invoice = I('invoice_title'); // 留言
        $pay_points =  I("pay_points",0); //  使用积分
        $user_money =  I("user_money",0); //  使用余额  
		$total= I('total');
        $user_money = $user_money ? $user_money : 0;

        if(!$address_id) exit(json_encode(array('status'=>-3,'msg'=>'请先填写收货人信息','result'=>null))); // 返回结果状态
        if(!$shipping_code) exit(json_encode(array('status'=>-4,'msg'=>'请选择物流信息','result'=>null))); // 返回结果状态
	
		$order_goods = M('cart')->where("user_id =".$_SESSION['user']['id'])->select();
		//dump($order_goods);
		//添加订单
		function addOrder($user_id,$address_id,$shipping_code,$car_price,$total,$invoice)
		{
			
			 // 0插入订单 order
			 //Global $invoice;
			$address = M('address')->where("id =".$address_id)->find();
			$data = array(
					'numid'         => date('YmdHis').rand(1000,9999), // 订单编号
					'uid'          =>$_SESSION['user']['id'], // 用户id
					'consignee'        =>$address['name'], // 收货人
					'address'          =>$address['address'],//'详细地址',
					'tel'           =>$address['tel'],//'手机',
					'emailno'          =>$address['postcode'],//'邮编',            
					'shipping'    =>$shipping_code, //'物流名称',                                       
					'buy'     =>$total,// 订单总额
					'written'         =>$invoice,
					'addtime'         =>time(), // 下单时间                
			);
			//dump($data);
			$order_id = M("Order")->data($data)->add();
			//echo $order_id;
			if(!$order_id){ 
				return array('status'=>-8,'msg'=>'添加订单失败','result'=>NULL);        
			}
			$order = M('order')->where("orderid =".$order_id)->find();                
			
			$cartList = M('cart')->where("user_id =".$_SESSION['user']['id'])->select();
			foreach($cartList as $key => $val)
			{
			   
			   $data2['oid']          = $order_id; // 订单id
			   $data2['gid']          = $val['goods_id']; // 商品id
			   $data2['uid']          = $_SESSION['user']['id'];
			   $data2['num']          = $val['goods_num']; // 购买数量
			   $data2['goodsname']   = $val['goods_name'];
			   $pic = M('goods_pic')->where("gid =".$val['goods_id'])->find();
			   $data2['pic']          = $pic['picname'];
			   $data2['guige']        = '';
			   $data2['price']        = $val['goods_price']; // 商品价
			   $order_goods_id        = M("orderdetail")->data($data2)->add(); 
			   
			   /** 扣除商品库存 **/ 
			   $store = M('goods')->where("id = ".$val['goods_id'])->Getfield('store');
			   $sto = $store - $val['goods_num'];
			   $map['store'] = $sto;
			   M('goods')->where("id = ".$val['goods_id'])->save($map);
			  
			}                        
			// 4 删除已提交订单商品
			M('Cart')->where("user_id =".$_SESSION['user']['id'])->delete();
		  
			return array('status'=>1,'msg'=>'提交订单成功','result'=>$order_id); // 返回新增的订单id        
		}

		 
        // 提交订单        
        if($_POST['act'] == 'submit_order')
        {      
		
            $result = addOrder($this->user_id,$address_id,$shipping_code,$car_price,$total,$invoice); // 添加订单                        
            exit(json_encode($result));            
        }           
    }

	public function cart4(){
		$oid = I('get.order_id');
		$list = M('order')->where('orderid='.$oid)->field('numid,buy')->find();
		$numid = $list['numid'];
		$total = $list['buy'];
		$date = date('Y-m-d',time()+24*3600); 
		$this->assign('oid',$oid);
		$this->assign('date',$date);
		$this->assign('numid',$numid);
		$this->assign('total',$total);
		$this->display('cart/cart3');
		
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
	
	//模拟支付
	public function pay(){
		$oid = I('oid');
		$map['payment'] = I('payment');
		$map['paystatus'] = '1';
		$a = M('order')->where('orderid='.$oid)->save($map);
		if($a){
			$this->ajaxReturn('1');
		}
	} 
}
