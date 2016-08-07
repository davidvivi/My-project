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
        $count = M('order')->count(); //查询总条数
        $Page = new \Think\Page($count,5);  
        $show = $Page->show();
        $orderlist = $order->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->select();

    
        foreach($orderlist as $k=>$v){  
            $orderlist[$k]['orderstatus'] = $orderstatus[$orderlist[$k]['orderstatus']];
            $orderlist[$k]['paystatus'] = $paystatus[$orderlist[$k]['paystatus']];
            $orderlist[$k]['payment'] = $payment[$orderlist[$k]['payment']];
            $orderlist[$k]['shipping'] = $shipping[$orderlist[$k]['shipping']];
            $orderlist[$k]['addtime'] = date('Y-m-d H:i:s',$orderlist[$k]['addtime']);
        }
        //dump($orderlist);
        //exit;
        $this->assign('page',$show);
        $this->assign('count', $count);
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
        //exit;
        $order = M('order');
        $shipping = array('顺丰','圆通','申通','中通');
        $orderstatus = array('新订单','已发货','已收货','无效订单');

        //根据orderid查询出来一组数据
        $order = M('order')->where("orderid = $orderid")->find();
        $order['orderstatus'] = $orderstatus[$order['orderstatus']];
        $order['shipping'] = $shipping[$order['shipping']];


        
        $uid = $order['uid'];
        //查询会员信息
        $user = M('user')->where('id='.$uid)->find();
        //dump($user);
        //exit;

        //查询商品信息
        $goods = M('orderdetail')->where('oid='.$orderid)->select();
        //dump($goods);

        //查询图片的信息
        foreach($goods as $k=>$v){  
            $res = M('goods')->where('id='.$v['gid'])->find();
            //dump($res);
            $goods[$k]['addtime'] = date("Y-m-d",$res['addtime']);
        }
        //dump($goods);
        //exit;
        $this->assign('goods',$goods);
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


    /*
     * 订单删除
     */
    public function order_delete()
    {   
        
        $orderid = I('get.orderid');
        //dump($orderid);
        //exit;
        $order = M('order');
        //$orderdetail = M('orderdetail');

        $a = M('order')->where('orderid='.$orderid)->delete();
        //echo $a;

        //echo $order->getLastSql();
        //dump($a);
        //exit; 
        //$a = 1

        //dump($a);
        //exit;
        $b = M('orderdetail')->where('oid='.$orderid)->delete();
        //$a = 1;
        //$b = 1;

        if($a && $b){   
            //echo $_SERVER['HTTP_REFERER'];
            $this->success('删除订单成功',U('index'),3);
            //redirect(U('order/index'));
        }else{  
            $this->error('订单删除失败');           
        }   
    }


    /*
    *  发货单列表
    */
    public function fahuo()
    {   
        $order = M('order');
        $orderstatus = array('新订单','已发货','已收货','无效订单');
        $paystatus = array('未支付','已支付');
        $payment = array('货到付款','微信支付','支付宝','银联支付');
        $shipping = array('顺丰','圆通','申通');

        $data['orderstatus'] = 0;
        $data['paystatus'] =1;

        $orderlist = M('order')->where($data)->select();
        //dump($orderlist);
        //exit;
        $count = M('order')->where($data)->count(); //查询总条数
        //dump($count);
        //exit;
        $Page = new \Think\Page($count,6);  
        $show = $Page->show();
        $orderlist = $order->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->where($data)->select();

    
        foreach($orderlist as $k=>$v){  
            $orderlist[$k]['orderstatus'] = $orderstatus[$orderlist[$k]['orderstatus']];
            $orderlist[$k]['paystatus'] = $paystatus[$orderlist[$k]['paystatus']];
            $orderlist[$k]['payment'] = $payment[$orderlist[$k]['payment']];
            $orderlist[$k]['shipping'] = $shipping[$orderlist[$k]['shipping']];
            $orderlist[$k]['addtime'] = date('Y-m-d H:i:s',$orderlist[$k]['addtime']);
        }
        //dump($orderlist);
        //exit;
        $this->assign('page',$show);
        $this->assign('count', $count);
        $this->assign('orderlist',$orderlist);

        $this->display('order/fahuo');
    }


    public function send()
    {   
        $orderid = I('id');

        $data['orderstatus'] = 1;
        $data['sendtime'] = time();

        $res = M('order')->where('orderid='.$orderid)->save($data);

        if($res){   
            $this->ajaxReturn('1');
        }else{  
            $this->ajaxReturn('0');
        }

    }



}   