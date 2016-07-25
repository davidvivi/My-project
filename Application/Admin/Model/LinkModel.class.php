<?php

	namespace Admin\Model;

	use Think\Model;

	// 
	class LinkModel extends Model
	{
	public function stateSwitch($data)
        {
            foreach($data as $key => $val)
            {   
                // 状态 0:已禁用；其他：已开启；
                if($val['state'] == 0){ 
                    $data[$key]['state'] = '已禁用';
                }else{ 
                    $data[$key]['state'] = '已开启'.$data[$key]['state'];
                }
            }      
            return $data;
        }
	}