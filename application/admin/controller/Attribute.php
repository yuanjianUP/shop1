<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/12/2
 * Time: 12:04
 */

namespace app\admin\controller;


use think\Db;

class Attribute extends Common
{
    public function add(){
        if(request()->isGet()){
            $cate = Db::name('type')->select();
            $this -> assign('cate',$cate);
            return $this -> fetch();
        }
        $data = input();
        if($data['attr_input_type']==1){
            unset($data['attr_values']);
        }else {
            if (!$data['attr_values']) {
                $this->error('属性值不能为空');
                return;
            }
        }
        if(isset($data['attr_values'])){
            $data['attr_values'] = trim($data['attr_values']);
        }

//        dump($data);exit;
        Db::name('attribute')->strict(false)->insert($data);
        $this->success('添加成功');
    }
    public function index(){
        $data = model('attribute')->getList();
        $this -> assign('data',$data);
        return $this -> fetch();
    }
    public function edit(){
        if(request()->isGet()){
            $cate = model('category')->getCate();
            $id = input('id');
            $data = model('attribute')->getAttrByID($id);
            $this -> assign('data',$data);
            $this -> assign('cate',$cate);
            return $this -> fetch();
        }
        $data = input();
        $result = model('attribute')->setData($data);
        if(!$result){
            $this -> error('修改失败');
            return ;
        }
        $this -> success('成功');
    }

    public function del(){
        $result = Db::name('attribute')->delete(input('id'));
        if(!$result){
            $this -> error('删除失败');
            return ;
        }
        $this -> success('成功');
    }
}