<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/7
 * Time: 22:54
 */

namespace app\admin\model;


use think\Model;

class Category extends Model
{
    public function getCate($id=null,$pid=0,$isNull=false){
        $cate = Category::all();
        return  $cate_tree = get_tree($cate,$id,$pid,0,$isNull);
    }

    public function del($id){
        $data = Category::get(['parent_id'=>$id]);
        if($data){
            $this -> error = '有子类不能删除';
            return false;
        }
        Category::where(['id'=>$id])->delete();
    }
}