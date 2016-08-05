<?php

namespace Home\Controller;

use Think\Controller;

class GdetailController extends Controller {
    
   public function index(){


	   $goodsId = I('goodsId');
       if(!$goodsId){   
            $goodsId = 25;
       }

       $detail = new \Home\Model\GdetailModel();
       
       $goodsdata = $detail->hostShop();
       //商品详细信息
       $GoodsDetail = $detail ->shows($goodsId); 
       //
       //dump($GoodsDetail);

       $assess = $this->assess($goodsId);
       
       $this->assign('assess',$assess);
       $this->assign('GoodsDetail', $GoodsDetail);
       $this->assign('goodsdata',$goodsdata);
	   $this->display();
   }

   public function assess($data)
   {    
        $Assess = D('assess');
        $User = D('user');
        $assessInfo['count'] = $Assess -> where('goodsid ='.$data) -> count();
        //$assessinfo = $Assess -> where('goodsid = '.$data)->select();
        foreach($assessinfo as $k => $v){ 
            $assessinfo[$k]['addtime'] = date('Y-m-d',$assessinfo[$k]['addtime']);
            $username = $User -> field('username') -> where('id ='.$v['uid']) -> select();
            $assessinfo[$k]['username'] = $username[0]['username'];
        }
        
        $data = $assessinfo;
        return $data; 
   }
}
