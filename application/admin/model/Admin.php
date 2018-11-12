<?php

/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/6
 * Time: 21:40
 */
namespace app\admin\model;
use think\Model;
class Admin extends Model
{
    public function check_login(){
        $captcha = input('captcha');
        $obj = new \think\captcha\Captcha();
        if($obj -> check($captcha)){
            $this -> error = '验证码错误';
        }
        $username = input('username');
        $password = md5(input('password'));
        $result = Admin::get(['username'=>$username,'password'=>$password]);
        //dump($result);exit;
        if(!$result){
            $this -> error = '用户名或密码错误';
            return false;
        }
        session('userid',$result->getAttr('id'));
    }
}