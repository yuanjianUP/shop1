<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/5
 * Time: 23:00
 */

namespace app\admin\controller;


use think\Controller;

class Login extends Controller
{
    public function login(){
        if ($this->request->isGet()){
            return $this->fetch();
        }
        $admin = model('Admin');
        $result = $admin -> check_login();
        if($result === false){
            $this->error($admin->getError());
        }
        $this -> success('登陆成功','admin/index/index');
    }
    public function captcha(){
        $obj = new \think\captcha\Captcha(['length'=>4]);
        return $obj -> entry();
    }

    public function makeMd5(){
        return md5(input('key'));
    }
}