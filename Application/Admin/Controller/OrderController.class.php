<?php

namespace Admin\Controller;

use Think\Controller;

class OrderController extends CommonController {

    public function index()
    {
        $order = M('order');
        $orderstatus = array('新订单','已发货','已收货','无效订单');
        $paystatus = array('未支付','已支付');
        $payment = array('货到付款','微信支付','支付宝','银联支付');
        $shipping = array('顺丰','圆通','申通','中通');

        $orderlist = M('order')->field('id,uid,numid,buy,written,emailno,consignee,address,tel,orderstatus,addtime,paystatus,payment,shipping')->select();
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
}