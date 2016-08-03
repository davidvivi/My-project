<?php

namespace Home\Model;

use Think\Model;

// 模型的名字与表名要一致
class IndexModel extends Model
{
    protected $trueTableName = 'think_category';
    
    public function cateData()
    {

        $category = M('category');
        $firstcate = $category->field('id,pid,path,name')->select();
        // 计算路径中的逗号有几个，可以得知几级分类
        foreach($firstcate as $key => $val){
            $num = substr_count($val['path'],',');

            if($num == 1){
                $catedata[$key] = $val;
                
                $pidtwo = $val['id']; 
                // 得到二级分类
                $data = $category->field('id,pid,name')->where("pid='{$pidtwo}'")->select();
                $catedata[$key]['seconddata'] = $data; 
                foreach($data as $k => $v){ 
                    $pidthird = $v['id'];
                    //得到三级分类
                    $thirddata = $category->field('id,pid,name')->where("pid='{$pidthird}'")->select();
                    // 把三级分类放到二级的son里
                    $catedata[$key]['seconddata'][$k]['son'] = $thirddata;
                }
 
            }
        }

        // dump($catedata);  
        return $catedata;      
    }
    // 得到热卖商品
    public function hostShop()
    {

        $goods = M('goods');
        $goods_pic = M('goods_pic');
        $goodsdata = $goods->field('id,goodname,price,buy')->where('state=1')->order('buy desc')->limit(5)->select();   
        foreach($goodsdata as $key => $val){ 
            $gid = $val['id'];
            $picname = $goods_pic->where("gid='{$gid}'")->getField('picname');
            $goodsdata[$key]['picname'] = $picname;
            $goodsdata[$key]['saleprice'] = intval($val['price'] / 0.9);
        }
        return $goodsdata;
    }

    
}
