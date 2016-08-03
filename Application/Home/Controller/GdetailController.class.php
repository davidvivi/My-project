<?php

namespace Home\Controller;

use Think\Controller;

class GdetailController extends Controller {
    
   public function index(){


	   $goodsId = I('goodsId');
        dump($goodsId);
       if(!$goodsId){   
            $goodsId = 25;
       }
       $detail = new \Home\Model\GdetailModel();
       $goodsdata = $detail->hostShop();
       $GoodsDetail = $detail ->shows($goodsId); 
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
        $assessinfo = $Assess -> where('goodsid = '.$data)->select();
        foreach($assessinfo as $k => $v){ 
            $assessinfo[$k]['addtime'] = date('Y-m-d',$assessinfo[$k]['addtime']);
            $username = $User -> field('username') -> where('id ='.$v['uid']) -> select();
            $assessinfo[$k]['username'] = $username[0]['username'];
        }
        
        $data = $assessinfo;
        dump($assessinfo);
        return $data; 
   }


}
