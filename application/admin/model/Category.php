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
    public function getCate($pid=null){
        $cate = Category::all();
        return  $cate_tree = get_tree($cate,$pid);
    }
}