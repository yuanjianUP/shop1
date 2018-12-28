<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/17
 * Time: 21:56
 */

namespace app\admin\controller;


use think\Db;

class Role extends Common
{
    public function add(){
        if(request()->isGet()){
            return $this -> fetch();
        }
        Db::name('role')->insert(['role_name'=>input('role_name')]);
        $this->success('ok','index');
    }
    public function index(){
        $data = Db::name('role')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function disfetch(){
        if(request()->isGet()){
            $rule = model('rule')->getRules();
            $this -> assign('rules',$rule);
            $hasRule = db('role_rule')->field('rule_id')->where(['role_id'=>input('id')])->select();
            $rule_ids = [];
            foreach ($hasRule as $key => $value) {
                $rule_ids[] = $value['rule_id'];
            }
            $rule_ids = implode(',',$rule_ids);
            $this -> assign('hasRule',$rule_ids);
            return $this -> fetch();
        }
        $role_id = input('id/d',0);
        if($role_id<=1){
            $this -> error('错误参数');
        }
        $rule_list = input('rule/a');
        $list = [];
        foreach ($rule_list as $key=>$value){
            $list[] = ['role_id'=>$role_id,'rule_id'=>$value];
        }
        Db::name('role_rule')->where(['role_id'=>$role_id])->delete();
        if($list){
            Db::name('role_rule')->insertAll($list);
        }
        $this -> success('成功');
    }

}
