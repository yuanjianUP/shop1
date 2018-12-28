<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/2
 * Time: 10:01
 */

namespace app\admin\controller;


use think\Db;

class Type extends Common
{
    public function add(){
        if(request()->isGet()){
            return $this -> fetch();
        }
        $type_name['type_name'] = input('type_name');
        $result = Db::table('shop_type')->insert($type_name);
        if(!$result){
            $this -> error('添加失败');
            return ;
        }
        $this -> success('添加成功');

    }
    public function index(){
        if(request()->isGet()){
            $data = Db::table('shop_type')->select();
            $this -> assign('data',$data);
            return $this -> fetch();
        }
    }
    public function edit(){
        if(request()->isGet()){
            $id = input('id');
            $data = Db::table('shop_type')->find($id);
            $this -> assign('data',$data);
            return $this -> fetch();
        }
        $result = Db::table('shop_type')
            ->where(['id'=>input('id')])
            ->setField(['type_name'=>input('type_name')]);
        if(!$result){
            $this -> error('修改失败');
            exit;
        }
        $this -> success('修改成功');
    }
    public function del(){
        $result = Db::table('shop_type')->delete(input('id'));
        if(!$result){
            $this -> error('删除成功');
            exit;
        }
        $this -> success('成功');
    }

}