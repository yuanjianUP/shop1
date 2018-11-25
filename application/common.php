<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function strtolowers($str){
	return strtolower($str);
}
function makeU($str){
	return 'http://res.tp5.com'.url($str);
}

if(!function_exists('get_tree')){
    /**
     * @param $data
     * @param int $id
     * @param null $pid
     * @param int $lev
     * @return array
     */
    function get_tree($data,$id=null,$pid=0,$lev=0,$isNull=false){
        static $list = [];
        if($isNull){
            $list = [];
        }
        foreach($data as $key=>$value){
            if($value['id']==$id){
                continue;
            }
            if($value['parent_id'] == $pid){
                $value['lev'] = $lev;
                $list[] = $value;
                get_tree($data,$id,$value['id'],$lev+1,$isNull);
            }
        }
        return $list;
    }
}