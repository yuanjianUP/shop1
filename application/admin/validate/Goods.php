<?php

/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/17
 * Time: 11:20
 */
namespace app\admin\validate;
use think\Validate;

class Goods extends Validate
{
    protected $rule=[
        'goods_name|商品名称'=>'require|max:20',
        'cate_id|商品分类'=>'require|gt:0',
        'shop_price|本店售价'=>'require|checkPrice',
    ];
    protected $message=[
        'goods_name.max'=>'商品名称最大20个字符',
        'shop_price.checkPrice'=>'本店售价应低于市场售价',
    ];
    public function checkPrice($value,$rule,$data){
        if($value > $data['market_price']){
            return false;
        }
        return true;
    }
}