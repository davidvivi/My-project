<?php

	namespace Admin\Model;

	use Think\Model;

	// 
	class CategoryModel extends Model
	{
        
        public function categoryInfo($first,$last)
        { 
            // 配置
            // 数据库地址
            define('HOST',C('DB_HOST'));
            // 用户名
            define('USER',C('DB_USER'));
            // 用户密码
            define('PASS',C('DB_PWD'));
            //数据库
            define('DB',C('DB_NAME'));
            // 字符集
            define('CHARSET','utf8');
            // 表前缀
            define('PIX',C('DB_PREFIX'));

            // 1.连接
            $link = mysqli_connect(HOST,USER,PASS,DB) or exit('连接失败！');
            // 2.设置字符集
            mysqli_set_charset($link,CHARSET);

            // 3.准备sql
            $sql = "select id,pid,name,path from " . PIX . "category order by concat(`path`,`id`) limit " .$first. "," .$last;
            // 4.执行查询
            $result = mysqli_query($link,$sql);
            // 5.准备空数组接收查询出来的数据
            $catelist = array();
            // 6.遍历结果集所有数据
            while($row = mysqli_fetch_assoc($result)){
                $catelist[] = $row;
            }
            // 7.释放结果集
            mysqli_free_result($result);
            // 8.关闭连接
            mysqli_close($link);
            return $catelist;
             
        }




		public function cateSwitch($data)
        {
            foreach($data as $key => $val)
            {   
                // 计算路径中的逗号有几个，可以得知几级分类
                $num = substr_count($val['path'],',');
                // 根据逗号去填充字符串 |--
                $str = str_repeat('|--',$num - 1);
                $data[$key]['name'] = $str . $val['name'];
                $data[$key]['num'] = $num;
            }   
            return $data;
        }
        // 把pid变成父类
        public function pidSwitch($data)
        { 
            $category = M('category');
            $alldata = $category->field('id,pid,name')->select();
            foreach($data as $k => $v)
            { 
                if($v['pid'] == 0){ 
                    $data[$k]['father'] = '这已经是一级分类';
                }else{ 
                    foreach($alldata as $key => $val){
                        
                        if($val['id'] == $v['pid']){ 
                           $data[$k]['father'] = $val['name']; 
                        }
                    }
                }
            }
            return $data;
        }

	}