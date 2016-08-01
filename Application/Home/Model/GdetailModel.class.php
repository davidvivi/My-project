<?php

namespace Home\Model;

use Think\Model;

// 模型的名字与表名要一致
class GdetailModel extends Model
{

    protected $trueTableName = 'think_goods'; 
    public function shows($data)
    {
    $Good = D('goods');
    $detail = $Good -> where('id ='.$data)->find();
    $detail['addtime'] = date('Y-m-d',$detail['addtime']);
    $data = $this->deal($detail);
    //dump($detail);
    return $data;
    }

    public function deal($data)
    {   
        $Cate = D('category');
        //$goods = D('goods');

        $cateid = $data['typeid'];
        //dump($cateid);
        $catename = $Cate -> field('name')->where("path = '{$cateid}'")->find();
        $data['typename'] = $catename['name'];
        //dump($data);
        return $data;
    }
    public function assess()
    {   
        $Assess = M('assess');
        $Assessuid = M('user');
        $assess = $Assess->select();
    


    }
   
     public function hostShop()
    {

        $goods = M('goods');
        $goods_pic = M('goods_pic');
        $goodsdata = $goods->field('id,goodname,price,buy')->where('state=1')->order('buy desc')->limit(5)->select();   
        foreach($goodsdata as $key => $val){ 
            $gid = $val['id'];
            $picname = $goods_pic->where("gid='{$gid}'")->getField('picname');
            $goodsdata[$key]['picname'] = $picname;
            $goodsdata[$key]['saleprice'] = $val['price'] * 0.9;
        }
        return $goodsdata;
    }       
}