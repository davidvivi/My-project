<?php

namespace Admin\Controller;

use Think\Controller;

class OrderController extends CommonController {

    /*
    *   订单列表
    */
    public function index()
    {
        $order = M('order');
        $orderstatus = array('新订单','已发货','已收货','无效订单');
        $paystatus = array('未支付','已支付');
        $payment = array('货到付款','微信支付','支付宝','银联支付');
        $shipping = array('顺丰','圆通','申通','中通');

        $orderlist = M('order')->field('orderid,uid,numid,buy,written,emailno,consignee,address,tel,orderstatus,addtime,paystatus,payment,shipping')->select();
        //dump($orderlist);

        foreach($orderlist as $k=>$v){  
            $orderlist[$k]['orderstatus'] = $orderstatus[$orderlist[$k]['orderstatus']];
            $orderlist[$k]['paystatus'] = $paystatus[$orderlist[$k]['paystatus']];
            $orderlist[$k]['payment'] = $payment[$orderlist[$k]['payment']];
            $orderlist[$k]['shipping'] = $shipping[$orderlist[$k]['shipping']];
            $orderlist[$k]['addtime'] = date('Y-m-d H:i:s',$orderlist[$k]['addtime']);
        }
        //dump($orderlist);

        $this->assign('orderlist',$orderlist);
        $this->display('order/order-list');
            
    }


    /*
    *   订单详情
    */
    public function order_detail()
    { 
        //获取查看的订单的id
        $orderid = I('get.orderid');
        //dump($orderid);
        $order = M('order');
        $shipping = array('顺丰','圆通','申通','中通');
        $orderstatus = array('新订单','已发货','已收货','无效订单');

        //根据orderid查询出来一组数据
        $order = M('order')->where("orderid = $orderid")->find();
        $order['orderstatus'] = $orderstatus[$order['orderstatus']];
        $order['shipping'] = $shipping[$order['shipping']];


        
        $uid = $order['uid'];
        //查询会员信息
        $user = M('user')->where("id = $uid")->find();
        $this->assign('user',$user);
        $this->assign('order',$order);

        $this->display();
    }
    
    /*
    * 订单编辑
    */
    public function order_edit()
    {    
        $orderid = I('get.orderid');
        $order = M('order');
        $order = M('order')->where("orderid = $orderid")->find();
        $shipping = array('顺丰','圆通','申通','中通');
        $orderstatus = array('新订单','已发货','已收货','无效订单');

        $order['orderstatus'] = $orderstatus[$order['orderstatus']];
        
        //$order['shipping'] = $shipping[$order['shipping']];
    
        $this->assign('shipping',$shipping);
        $this->assign('order',$order);

        $this->display();
    }


    public function next()
    { 
    }

}