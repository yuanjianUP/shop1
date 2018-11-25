<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/20
 * Time: 20:49
 */

namespace app\admin\controller;


use think\Controller;

class Test extends Controller
{
        public function errorMessage(){
            return 'Error line ' . $this->getLine().' in ' . $this->getFile()
                      .': <b>' . $this->getMessage() . '</b> Must in (0 - 60)';
        }
        public function test(){
            $age = 10;
            try{
               $age = intval($age);
               if($age>60){
                    throw new Test($age);
               }
            }catch (){

            }
        }
}