<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/18
 * Time: 22:29
 */

namespace app\admin\model;


use think\Model;

class Rule extends Model
{
    public function getRules(){
        $data = Rule::all();
        return get_tree($data);

    }
}