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
        //查询头像
        $picdata = M('user_detail')->where('uid='.$uid)->find();
        $pictime = $picdata['picaddtime'];
        $picdata['picaddtime'] = date('Y-m-d',$picdata['picaddtime']);
        //dump($picdata);
        //exit;

        //查询首页的订单部分
        $order = M('order')->order('orderid desc')->where('uid='.$uid)->limit(6)->select();
        
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


        //遍历推荐商品
        $goods = M('goods');
        $goods_pic = M('goods_pic');
        $goodsdata = $goods->field('id,goodname,price,buy,addtime')->where('state=1')->order('buy desc')->limit(4)->select();  
        foreach($goodsdata as $key => $val){ 
            $gid = $val['id'];
            $picname = $goods_pic->where("gid='{$gid}'")->getField('picname');
            $goodsdata[$key]['picname'] = $picname;
            $goodsdata[$key]['saleprice'] = intval($val['price'] / 0.9);
            // 时间转换
            $goodsdata[$key]['addtime'] = date('Y-m-d',$goodsdata[$key]['addtime']);
        }
        //dump($goodsdata);
        //exit;


        $this->assign('pictime',$pictime);
        $this->assign('picdata',$picdata);
        $this->assign('goodsdata',$goodsdata);
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
        $uid = $_SESSION['user']['id'];
        //查询图片
        $picdata = M('user_detail')->where('uid='.$uid)->find();
        $pictime = $picdata['picaddtime'];

        $picdata['picaddtime'] = date('Y-m-d',$picdata['picaddtime']);
            
        //根据用户名查到手机号
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();
        $list = M('user_detail')->field('email')->where("name='$name'")->find();


        $this->assign('pictime',$pictime);
        $this->assign('picdata',$picdata);
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
            $uid = $_SESSION['user']['id'];
        }    
        //查询图片
        $picdata = M('user_detail')->where('uid='.$uid)->find();
        $pictime = $picdata['picaddtime'];

        $picdata['picaddtime'] = date('Y-m-d',$picdata['picaddtime']);
        //dump($pictime);
        //dump($picdata);
        //exit;
        
        //根据用户名查到等级
        $list=D('user_detail')->field('grade')->where("name='$name'")->find();
        $grade = $list['grade'];
        $data=D('user')->field('tel,id,password,addtime')->where("username='$name'")->find();
        $gradearr=array('','英勇黄铜','不屈白银','璀璨钻石');
        $dengji = $gradearr[$grade];    

        //查询用户详细信息
        $detail = M('user_detail')->where('uid='.$uid)->find();
        //dump($detail);
        //exit;        
        //dump($dengji); 
        //dump($grade);
        //dump($data);
        //exit;
        $this->assign('pictime',$pictime);
        $this->assign('picdata',$picdata);
        $this->assign('detail',$detail);
        $this->assign('dengji',$dengji);
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('grade',$grade);
        $this->display(''); 
    }

    public function userinfoForm()
    { 
        $uid = I('uid');
        $nickname = I('nickname');
        $address = I('address');
        $sex = I('sex');
        
        $data['nickname'] = $nickname;
        $data['address'] = $address;
        $data['sex'] = $sex;

        $res = M('user_detail')->where('uid='.$uid)->save($data);
        
        if($res){   
            $this->ajaxReturn('1');
        }else{  
            $this->ajaxReturn('0');
        }

    }  

    public function avatar()
    {   
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $uid = $_SESSION['user']['id'];
        }
        //dump($uid);
        $picdata = M('user_detail')->where('uid='.$uid)->find();
        $pictime = $picdata['picaddtime'];
        $picdata['picaddtime'] = date('Y-m-d',$picdata['picaddtime']);
        //dump($picdata);
        //exit;
        $this->assign('pictime',$pictime);
        $this->assign('picdata',$picdata);
        $this->assign('uid',$uid);
        $this->display();
    }

    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize = 3145728 ;// 设置附件上传大小    
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
        $upload->rootPath = './Public/';
        $upload->savePath  = './userpicUploads/'; // 设置附件上传目录               
        $info = $upload->upload();
        $uid = I('uid');

        
        $data['pic']=$info['photo']['savename'];
        $data['picaddtime'] = time();


        if(!$info) {
            // 上传错误提示错误信息        
            $this->error($upload->getError());    
        }else{
            M('user_detail')->where('uid='.$uid)->save($data);
            $this->success('上传成功！');        
        }
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
        $Page  = new \Think\Page($count,3);

        $show = $Page->show();
        $order_str = "orderid DESC";
        $orderlist = M('order')->order($order_str)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        //dump($orderlist);
        //exit;
        
        //获取订单详情
        foreach($orderlist as $k=>$v){  
            
            //查找订单的名字和图片
            $orderdetail= M('orderdetail')->where('oid='.$v['orderid'])->select();
            
            foreach($orderdetail as $key=>$val){    
                $goodsaddtime = M('goods')->where('id='.$val['gid'])->find();
                //dump($goodsaddtime);
                $orderdetail[$key]['addtime']=date("Y-m-d",$goodsaddtime['addtime']);
                //dump($orderdetail);

            }
            $orderlist[$k]['goodslist']=$orderdetail;


        }
        //dump($orderdetail);
        
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
        //根据orderdetail的gid查询goods表中的添加时间
        foreach($orderdetail as $key=>$val){    
            $addtime = M('goods')->where('id='.$val['gid'])->find();
            $orderdetail[$key]['addtime'] = date('Y-m-d',$addtime['addtime']);
        }

        //dump($orderdetail);

        //exit;
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

    /*
    *  确认收货
    */

    public function order_confirm()
    {   
        $id = I('id');
        //dump($id);
        //exit;
        $data['orderstatus'] = 2;
        $data['confirmtime'] = time();

        $res =M('order')->where('orderid='.$id)->save($data);
        
        if($res){   
            $this->ajaxReturn('1');
        }else{  
            $this->ajaxReturn('0');
        }
    }


    /**
        我的评价
    */
    public function comment()
    {   
        //已经收到货的可以评论，先查到收到货的,状态orderstatus为2的
        if($_SESSION['user']){
            $name = $_SESSION['user']['name'];
            $uid = $_SESSION['user']['id'];            
        } 
        $data['uid'] = $uid;
        $data['orderstatus'] = 2;
        $count = M('order')->where($data)->count();
        $Page  = new \Think\Page($count,3);

        $show = $Page->show();
        $orderlist = M('order')->order('orderid DESC')->where($data)->limit($Page->firstRow.','.$Page->listRows)->select();

        //dump($data);
        //exit;
        foreach($orderlist as $k=>$v){  
            $orderdetail = M('orderdetail')->where('oid='.$v['orderid'])->select();
            foreach($orderdetail as $key=>$val){    
                $goodsaddtime = M('goods')->where('id='.$val['gid'])->find();
                //dump($goodsaddtime);
                $orderdetail[$key]['addtime']=date("Y-m-d",$goodsaddtime['addtime']);
                //dump($orderdetail);
            }
            $orderlist[$k]['goodslist']=$orderdetail;

        }


        //dump($orderlist);
        //exit;
        $this->assign('page',$show); 
        $this->assign('orderlist',$orderlist);
        $this->assign('orderdetail',$orderdetail);
        $this->display();
    }

    //添加评论
    public function add_comment()
    {   
        $oid = I('get.oid');
        $uid = I('get.uid');
        $gid = I('get.gid');
        $id = I('get.id');

        //dump($oid);
        //dump($uid);
        //dump($gid);
        //$data = M('address')->field('id,uid,address,name,tel,postcode')->where('id='.$id)->find();
        $this->assign('id',$id);
        $this->assign('oid',$oid);
        $this->assign('uid',$uid);
        $this->assign('gid',$gid);               
        $this->display('user/add_comment');
    }

    public function comupload(){
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize = 3145728 ;// 设置附件上传大小    
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
        $upload->rootPath = './Public/';
        $upload->savePath  = './comUploads/'; // 设置附件上传目录               
        $info = $upload->upload();
        $uid = I('uid');
        $oid = I('oid');
        $gid = I('gid');
        $id = I('id');
        $contents = I('contents');
        //dump($uid);
        //dump($oid);
        //dump($gid);
        //dump($contents);
        //dump($info);
        //exit;        
        $data['picname']=$info['photo']['savename'];
        $data['addtime'] = time();
        $data['uid'] = $uid;
        $data['goodsid'] = $gid;
        $data['odid'] = $id;
        $data['contents'] =$contents;
        $map['commentstate'] = 1;

        //dump($data);
        //exit;


        if(!$contents) {
            // 上传错误提示错误信息        
            $this->error($upload->getError());    
        }else{
            M('assess')->add($data);
            M('orderdetail')->where('id='.$id)->save($map);
            $this->success('上传成功！');        
        }
    }
}