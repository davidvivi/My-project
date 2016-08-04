<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends CommonController 
{
    public function index()
    {
        //如果session里面有user
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $uid = $_SESSION['user']['id'];            
        }    
        //查询首页的订单部分
        $order = M('order')->field('addtime,numid,buy')->where('uid='.$uid)->select();
        //dump($order);
        $count=count($order);
        //dump($count);
        //exit;

        //查询订单详情
        //$orderdetail = M('orderdetail')->where('oid'=)

        //根据用户名查到等级
        $list=D('user_detail')->field('grade')->where("name='$name'")->find();
        $grade = $list['grade'];
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();


        $this->assign('count',$count);
        $this->assign('order',$order);
        $this->assign('uid',$uid);
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('grade',$grade);
        $this->display('');        

    }

    /*
     *   退出
     */
    public function logout()
    {   
        session_unset();
        session_destroy();
        header("location:".U('Home/Index/index'));
        exit;
    }


 
    /**
        个人中心地址管理
    */
    /*
     *  用户地址添加页面
     */
    public function add_address()
    {
        $uid = $_SESSION['user']['id'];
        $this->assign('uid',$uid);
        $this->display('user/add_address');
    }

     /*
     *  用户地址编辑页面
     */
    public function edit()
    {   
        $id = I('get.id');
        $uid = I('get.uid');
        $data = M('address')->field('id,uid,address,name,tel,postcode')->where('id='.$id)->find();
        $this->assign('data',$data);
        $this->assign('uid',$uid);
        $this->assign('id',$id);               
        $this->display('user/edit_address');
    }

    /*
     *  用户地址编辑,添加处理
     */
    public function addressEditForm()
    {  
        //接收用户编辑时的id
        $id = I('id');
        $uid = I('uid');
        $consignee = I('consignee');
        $postcode = I('postcode');
        $tel = I('tel');
        $address = I('address');

        $data['uid']=$uid;
        $data['postcode'] = $postcode;
        $data['name'] = $consignee;
        $data['tel'] = $tel;
        $data['address'] = $address;        
        //如果有id,则是编辑否则是添加
        if($id){
            $res = M('address')->where('id='.$id)->save($data);
        }else{    
            $res = M('address')->add($data);
        }
                
        if($res){
           $this->ajaxReturn('1');
        }else{ 
            $this->ajaxReturn('0');
        }

    }

    /*
     *  用户地址表列
     */
    public function address_list()
    {   
        //根据session里面存的uid，查询address表；
        $uid = $_SESSION['user']['id'];
        $data = M('address')->field('id,uid,address,name,tel,postcode')->where('uid='.$uid)->select();
        //dump($data);
        //exit;
        $this->assign('uid',$uid);
        $this->assign('data',$data);
        $this->display();
    }

    /*
     *  用户地址删除处理
     */
    public function del_address()
    {   
        $id = I('id');
        if($id){ 
            $res = M('address')->where('id='.$id)->delete();
            if($res){ 
                $this->ajaxReturn('1');
            }else{ 
                $this->ajaxReturn('0');
            }
        }else{ 
            $this->ajaxReturn('0');
        }      
    }
    


    /**
        个人中心安全设置管理
    */
    public function safety_settings()
    {   
        $name = $_SESSION['user']['name'];
            
        //根据用户名查到手机号
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();
        $list = M('user_detail')->field('email')->where("name='$name'")->find();
        $this->assign('list',$list);
        $this->assign('name',$name);
        $this->assign('data',$data);
        $this->display('');

    }

    public function password()
    {   
        $name = $_SESSION['user']['name'];
        //根据name拿到自己的密码
        $data = M('user')->field('password')->where("username='$name'")->find();
        
        $pwd = $data['password'];
        //dump($pwd);
        //exit;
        $this->assign('pwd',$pwd);
        $this->assign('name',$name);
        $this->display();
    }

    /*
     *  用户地址编辑,添加处理
     */
    public function passwordEditForm()
    {  
        
        //得到的是真正的密码
        $pwd = I('pwd');
        $name = I('username');
        $bool = password_verify(I('old_password'),$pwd);

        $new_password = I('new_password');
        $confirm_password = I('confirm_password');
        if($bool && ($new_password==$confirm_password)){
            if(strlen($new_password)>=4){ 
                $hash = password_hash($new_password,PASSWORD_DEFAULT);
                $data['password'] = $hash;
                $res = M('user')->where("username='$name'")->save($data);   
                if($res){  
                    $this->ajaxReturn('1');
                }else{  
                    $this->ajaxReturn('2');
                }
            }else{  
                $this->ajaxReturn('4');
            }
        }else{  
            $this->ajaxReturn('3');
        }

    }


    /**
        个人中心信息管理
    */
    public function info()
    {   
        //如果session里面有user
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            
        }    
        
        //根据用户名查到等级
        $list=D('user_detail')->field('grade')->where("name='$name'")->find();
        $grade = $list['grade'];
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();

        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('grade',$grade);
        $this->display(''); 
    }

    /**
        我的订单列表
    */  
    public function order_list()
    {   
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $uid = $_SESSION['user']['id'];            
        } 
        $where = ' uid='.$uid;

        //条件搜索

        if(I('get.type')){
           $where .= C(strtoupper(I('get.type')));
        }
        // 搜索订单 根据商品名称 或者 订单编号
        $search_key = trim(I('search_key'));       
        if($search_key)
        {
          $where .= " and (numid like '%$search_key%' or orderid in (select oid from `".C('DB_PREFIX')."orderdetail` where goodsname like '%$search_key%') ) ";
        }
       
        $count = M('order')->where($where)->count();
        $Page  = new \Think\Page($count,2);

        $show = $Page->show();
        $order_str = "orderid DESC";
        $orderlist = M('order')->order($order_str)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        //dump($orderlist);
        //exit;
        
        //获取订单详情
        foreach($orderlist as $k=>$v){  
            
            //查找订单的名字和图片
            $orderdetail= M('orderdetail')->where('oid='.$v['orderid'])->select();
            $orderlist[$k]['goodslist']=$orderdetail;

        }
        //dump($orderlist);
        //exit;
        $this->assign('page',$show);
        $this->assign('orderdetail',$orderdetail);
        $this->assign('orderlist',$orderlist);
        $this->display();
    }


    public function order_detail()
    {   
        $id = I('get.id');
        $map['orderid'] = $id;
        $order_info = M('order')->where($map)->find();
        //dump($order_info);
            
        $orderdetail= M('orderdetail')->where('oid='.$order_info['orderid'])->select();
        //dump($orderdetail);


        
        $this->assign('order_info',$order_info);
        $this->assign('orderdetail',$orderdetail);
        $this->display();
    }

    /*
     * 当未支付时(paystatus=0)才可以取消订单，取消订单后将orderstatus改为4，即为无效订单
     */
    public function cancel_order()
    {   
        $orderid = I('get.orderid');
        //dump($id);
        $map['orderstatus'] = 3;
        $res = M('order')->where('orderid='.$orderid)->save($map);
        if($res){   
            $this->success('订单取消成功',U('order_list'),1);
        }
    }




}