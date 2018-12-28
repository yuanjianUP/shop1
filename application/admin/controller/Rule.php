<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/18
 * Time: 22:19
 */

namespace app\admin\controller;


class Rule extends Common
{
    public function add(){
        $ruleModel = model('rule');
        if(request()->isGet()){
            $cate = $ruleModel->getRules();
            $this -> assign('rule',$cate);
            return $this -> fetch();
        }
        $data = input();
        $ruleModel -> strict(false) -> insert($data);
        $this -> success('ok','index');
    }
    public function index(){
        $rule = model('Rule')->getRules();
        $this -> assign('rule',$rule);

        return $this -> fetch();
    }
}