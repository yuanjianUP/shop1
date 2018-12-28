<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/2
 * Time: 21:12
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Attribute extends Model
{
    public function getList(){
        return $this -> alias('a')
            ->join('shop_type b','a.type_id=b.id','left')
            ->field('a.*,b.type_name')
            ->select();
    }
    public function getAttrByID($id){
        return Attribute::get($id);
    }
    public function setData($data){
        if(isset($data['attr_values'])){
            $data['attr_values'] = trim($data['attr_values']);
        }
       return Attribute::where(['id'=>$data['id']])
            ->strict(false)
            ->update($data);
    }
    public function getType($id){
        $data = Attribute::where(['type_id'=>$id])->select();
        $list = [];
        foreach ($data as $k=>$vo){
            $vo = $vo->toArray();
            if($vo['attr_input_type'] == 2){
                $vo['attr_values'] = explode(',',$vo['attr_values']);
            }
            $list[] = $vo;
        }
        return $list;
    }
    public function getGoodsA($goods_id){
      $list = Db::name('goods_attr')
            ->alias('a')
            ->join('shop_attribute b','a.attr_id=b.id','left')
            ->where(['a.goods_id'=>$goods_id])
            ->select();
      $data = [];
      foreach ($list as $key => $value){
          if($value['attr_input_type'] == 2){
              $value['attr_values'] = explode(',',$value['attr_values']);
          }
          $data[] = $value;
      }
      return $data;
    }

}