<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/20
 * Time: 20:49
 */

namespace app\admin\controller;


use think\Controller;
use think\Exception;

class Test extends Controller
{
    public function wechar(){
        return $this->fetch();
    }
    public function test(){
        $num = 0;
        try{
            echo 1/$num;
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}