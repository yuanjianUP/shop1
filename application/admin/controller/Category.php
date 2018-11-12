<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/6
 * Time: 23:06
 */

namespace app\admin\controller;


use think\Db;

class Category extends Common
{
    public function categoryadd(){
        if(request()->isGet()){
            $model = model('category');
            $cate_tree = $model->getCate();
            //dump($cate_tree);exit;
            $this -> assign('cate_tree',$cate_tree);
            return $this -> fetch();
        }
    }
    public function add(){
        $parent_id = input('parent_id');
        $name = input('cname');
        $data = [
            'name'=>$name,
            'parent_id'=>$parent_id
        ];
        $result = Db::name('category')->insert($data);
        if($result){
            $this -> success('添加成功','categoryadd');
        }else{
            $this -> error('添加失败');
        }
    }

    public function index(){
        $model = model('category');
        $data = $model->getCate();
        $this -> assign('data',$data);
        return $this -> fetch();
    }

    public function edit(){
        $id = input('id');
        $model = model('category');
        if(request()->isGet()){
            $data = $model->get(['id'=>$id]);
            $cate = $model->getCate($id);
            $this -> assign('cate',$cate);
            $this -> assign('data',$data);
            return $this -> fetch();
        }


    }
}