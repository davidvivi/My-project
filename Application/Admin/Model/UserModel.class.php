<?php

	namespace Admin\Model;

	use Think\Model;

	// 
	class UserModel extends Model
	{
		public function userSwitch($data)
        {
            foreach($data as $key => $val)
            {   
                // 状态
                if($val['status'] == 1){ 
                    $data[$key]['status'] = '已启用';
                }else if($val['status'] == 0){ 
                    $data[$key]['status'] = '已禁用';
                }else{
                    $data[$key]['status'] = '';
                }

                // 性别
                if($val['sex'] == 1){ 
                    $data[$key]['sex'] = '男';
                }else if($val['sex'] == 0){ 
                    $data[$key]['sex'] = '女';
                }else{
                    $data[$key]['sex'] = '';
                }

                // 等级
                $grade_arr = array('','英勇黄铜','不屈白银','璀璨钻石');
                if($val['grade'] >= 1 && $val['grade'] < 6){ 
                    $data[$key]['grade'] = $grade_arr[$val['grade']];
                }else{
                    $data[$key]['grade'] = '';
                }

                // 时间
                $data[$key]['addtime'] = date('Y-m-d H:i:s',$val['addtime']);

            }
            
            return $data;
        }
	}