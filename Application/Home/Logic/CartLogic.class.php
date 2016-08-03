<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */

namespace Home\Logic;

use Think\Model\RelationModel;
    /**
     *  添加一个订单
     * @param type $user_id  用户id     
     * @param type $address_id 地址id
     * @param type $shipping_code 物流编号
     * @param type $invoice_title 发票
     * @param type $car_price 各种价格
     * @return type $order_id 返回新增的订单id
     */
    public function addOrder($user_id,$address_id,$shipping_code,$invoice_title,$car_price)
    {
		switch($shipping_code){
			case '0':
				$shipping_name = '顺丰';
				break;
			case '1':
				$shipping_name = '申通';
				break;
			case '2':
				$shipping_name = '圆通';
				break;
		}
         // 0插入订单 order
        $address = M('address')->where("id =".$address_id)->find();
        $shipping = M('Plugin')->where("code = '$shipping_code'")->find();
        $data = array(
                'numid'         => date('YmdHis').rand(1000,9999), // 订单编号
                'uid'          =>$user_id, // 用户id
                'consignee'        =>$address['name'], // 收货人
                'address'          =>$address['address'],//'详细地址',
                'tel'           =>$address['tel'],//'手机',
                'postcode'          =>$address['postcode'],//'邮编',            
                'email'            =>$address['email'],//'邮箱',
                'shipping'    =>$shipping_name, //'物流名称',                       
                'invoice_title'    =>$invoice_title, //'发票抬头',                
                'total_amount'     =>$total,// 订单总额
                   
                'add_time'         =>time(), // 下单时间                
        );
        
        $order_id = M("Order")->data($data)->add();
        if(!$order_id)  
            return array('status'=>-8,'msg'=>'添加订单失败','result'=>NULL);
        
        // 记录订单操作日志
        logOrder($order_id,'您提交了订单，请等待系统确认','提交订单',$user_id);        
        
        $order = M('Order')->where("id = $order_id")->find();                
        
        $cartList = M('Cart')->where("user_id =$_SESSION['user']['id']")->select();
        foreach($cartList as $key => $val)
        {
           $goods = M('goods')->where("id = {$val['goods_id']} ")->find();
           $data2['oid']           = $order_id; // 订单id
           $data2['gid']           = $val['goods_id']; // 商品id
		   $data2['uid']           = $_SESSION['user']['id'];
           $data2['num']          = $val['goods_num']; // 购买数量
		   $pic = M('goods_pic')->where("gid = {$val['goods_id']} ")->find();
		   $data2['pic']          = $pic['picname'];
		   $data2['guige']        = '';
           $data2['price']        = $val['goods_price']; // 商品价
           $order_goods_id              = M("orderdetail")->data($data2)->add(); 
           // 扣除商品库存  扣除库存移到 付完款后扣除
           //M('Goods')->where("goods_id = ".$val['goods_id'])->setDec('store_count',$val['goods_num']); // 商品减少库存
        }                        
        // 4 删除已提交订单商品
        M('Cart')->where("user_id = $_SESSION['user']['id']")->delete();
      
        return array('status'=>1,'msg'=>'提交订单成功','result'=>$order_id); // 返回新增的订单id        
    }
    
    /**
     * 查看购物车的商品数量
     * @param type $user_id
     * $mode 0  返回数组形式  1 直接返回result
     */
    public function cart_count($user_id,$mode = 0){
        $count = M('Cart')->where("user_id = $_SESSION['user']['id']")->count();
        if($mode == 1) return  $count;
        
        return array('status'=>1,'msg'=>'','result'=>$count);         
    }
        
   /**
    * 获取商品团购价
    * 如果商品没有团购活动 则返回 0
    * @param type $attr_id
    * $mode 0  返回数组形式  1 直接返回result
    */
   public function get_group_buy_price($goods_id,$mode=0)
   {
       $group_buy = M('GroupBuy')->where("goods_id = $goods_id and ".time()." >= start_time and ".time()." <= end_time ")->find(); // 找出这个商品                      
       if(empty($group_buy))       
            return 0;
       
        if($mode == 1) return $group_buy['groupbuy_price'];
        return array('status'=>1,'msg'=>'','result'=>$group_buy['groupbuy_price']);       
   }  
   
   /**
    * 用户登录后 需要对购物车 一些操作
    * @param type $session_id
    * @param type $user_id
    */
   public function login_cart_handle($session_id,$user_id)
   {
	   if(empty($session_id) || empty($user_id))
	     return false;
        // 登录后将购物车的商品的 user_id 改为当前登录的id            
        M('cart')->where("session_id = '$session_id'")->save(array('user_id'=>$user_id));                    
        
        $Model = new \Think\Model();
        // 查找购物车两件完全相同的商品
        $cart_id_arr = $Model->query("select id from `__PREFIX__cart` where user_id = $user_id group by  goods_id,spec_key having count(goods_id) > 1");
        if(!empty($cart_id_arr))
        {
            $cart_id_str = implode(',', $cart_id_arr[0]);
            M('cart')->delete($cart_id_str); // 删除购物车完全相同的商品
        }
   }
}