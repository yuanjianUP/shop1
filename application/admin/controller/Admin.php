<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/18
 * Time: 20:53
 */

namespace app\admin\controller;


use think\Db;

class Admin extends Common
{
    public function add(){
        if(request()->isGet()){
            $data = Db::name('role')->where('id','gt','1')->select();
            $this -> assign('data',$data);
            return $this -> fetch();
        }
        $result = model('admin')->addUser();
        if($result === false){
            $this -> error(model('admin')->getError());
        }
        $this -> success('ok','index');
    }
    public function index(){
        $model = model('admin');
        $data = $model->listDate();
        $this -> assign('data',$data);
        return $this->fetch();
    }
}