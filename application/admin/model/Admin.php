<?php

/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/6
 * Time: 21:40
 */
namespace app\admin\model;
use think\Db;
use think\Model;
class Admin extends Model
{
    public function check_login(){
        $captcha = input('captcha');
        $obj = new \think\captcha\Captcha();
//        if($obj -> check($captcha)){
//            $this -> error = '验证码错误';
//        }
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

    public function addUser(){
        $data = input();
        $rule = [
            'username'=>'unique:admin',
            'password'=>'require',
        ];
        $obj = new \think\Validate($rule);
        if(!$obj->check($data)){
            $this -> error = $obj->getError();
            return false;
        }
        $data['password'] = md5($data['password']);
        Admin::startTrans();
        try{
            Admin::allowField(true)->isUpdate(false)->save($data);
            $admin_id = Admin::getLastInsID();
            Db::name('admin_role')->insert(['admin_id'=>$admin_id,'role_id'=>$data['role_id']]);
            Admin::commit();
        }catch (\Exception $e){
            Admin::rollback();
        }

    }
    public function listDate(){
        return Db::table('shop_admin')
            ->alias('a')
            ->join('shop_admin_role r','a.id= r.admin_id','left')
            ->join('shop_role b','b.id=r.role_id','left')
            ->field('a.username,b.role_name,r.*')
            ->select();
    }
}