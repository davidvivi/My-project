<?php

	namespace Admin\Model;

	use Think\Model;

	// 
	class UserModel extends Model
	{
        // 已查出user表信息，再查userdetail表信息
        public function userInfo($userdata)
        { 
            for($i=0;$i<count($userdata);$i++)
            {
                $userdetail = M('user_detail');
                $userid = $userdata[$i]['id'];              
                $detaildata = $userdetail->field('sex,email,address,grade')->where("uid='{$userid}'")->select();
                
                $userdata[$i]['sex'] = $detaildata[0]['sex'];
                $userdata[$i]['email'] = $detaildata[0]['email'];
                $userdata[$i]['address'] = $detaildata[0]['address'];
                $userdata[$i]['grade'] = $detaildata[0]['grade'];
                 
            }
            return $userdata; 
        }




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
                    $data[$key]['status'] = NULL;
                }

                // 性别
                if($val['sex'] == 1){ 
                    $data[$key]['sex'] = '男';
                }else if($val['sex'] == 0){ 
                    $data[$key]['sex'] = '女';
                }else{
                    $data[$key]['sex'] = NULL;
                }

                // 等级
                $grade_arr = array('','英勇黄铜','不屈白银','璀璨钻石');
                if($val['grade'] >= 1 && $val['grade'] < 6){ 
                    $data[$key]['grade'] = $grade_arr[$val['grade']];
                }else{
                    $data[$key]['grade'] = NULL;
                }

                // 时间
                $data[$key]['addtime'] = date('Y-m-d H:i:s',$val['addtime']);

            }
            
            return $data;
        }
	}