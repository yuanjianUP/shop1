<?php
namespace app\admin\controller;
use think\Db;
class Index extends Common
{
    public function index(){
        return $this -> fetch();
    }
    //头部
    public function top(){
        return $this -> fetch();
    }
    public function main(){
        return $this -> fetch();
    }
    public function menu(){
        $this->assign('menus',$this -> _user['menu']);
        return $this -> fetch();
    }
}
