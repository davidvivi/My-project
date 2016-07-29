<?php

namespace Home\Model;

use Think\Model;

// 模型的名字与表名要一致
class CategoryModel extends Model
{

    public function idHandle($getid)
    {

        $category = M('category');
        $data = $category->field('pid,path,name')->where("id='{$getid}'")->find();
        // 计算路径中的逗号有几个，可以得知几级分类
        $num = substr_count($data['path'],',');
        $cate['num'] = $num;
        // 根据几级分类得到它的一级分类id
        if($num == 3){ 
            // 得到它的父类id 即二级分类id  secondid
            $secondid = $data['pid'];
            // 得到二级分类名
            $secondname = $category->where("id='{$secondid}'")->getField('name');
            // 得到它的一级分类id  firstid 
            $firstid = $category->where("id='{$secondid}'")->getField('pid');
            // 得到它的一级分类名
            $firstname = $category->where("id='{$firstid}'")->getField('name');
            // 把分类名给返回变量
            $cate['firstname'] = $firstname;
            $cate['secondname'] = $secondname;
            $cate['thirdname'] = $data['name'];
            // 把分类id给返回变量
            $cate['firstid'] = $firstid;
            $cate['secondid'] = $secondid;
            $cate['thirdid'] = $getid;

        }else if($num == 2){ 
            // 它的父类id就是它的一级分类id 
            $firstid = $data['pid'];
            // 得到一级分类名
            $firstname = $category->where("id='{$firstid}'")->getField('name');
            // 二级分类名
            $secondname = $data['name'];
            // 把分类名给返回变量
            $cate['firstname'] = $firstname;
            $cate['secondname'] = $secondname;

            // 把分类id给返回变量
            $cate['firstid'] = $firstid;
            $cate['secondid'] = $getid;

        }else{ 
            // 它的id就是它的一级分类id
            // 把一级分类id给返回变量
            $cate['firstid'] = $getid;
            // 把分类名给返回变量
            $cate['firstname'] = $data['name'];
        }

        // 返回参数
        return $cate;
    }

}