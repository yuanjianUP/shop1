<?php 
// 1、申明命名空间  app是由于TP框架在载入的过程中将application目录所对应的命名空间进行替换 替换的结果为app
namespace app\admin\controller;
use think\Db;
use think\View;
class Goods extends Common
{
	public function add(){
        $model = model('goods');
        if(request()->isGet()){
            $cateModel = model('category');
            $cate = $cateModel->getCate();
            $this -> assign('cate',$cate);
            return $this -> fetch();
        }

        $result = $model -> addGoods();
        if($result === false) {
            $this->error($model->getError());
            return false;
        }
        $this -> success('添加成功');

    }
    public function index(){
	    $model = model('Goods');
	    $data = $model->getList();
	    //分类
        $cateModel = model('category');
        $cate = $cateModel -> getCate($pid=null,true);
//        dump($data);exit;
        $this -> assign('cate',$cate);
	    $this -> assign('data',$data);
	    return $this -> fetch();
    }

    public function edit(){
        $model = model('Goods');
        $id = input('goods_id');
        if(request()->isGet()){
            $data = $model -> getGoodsById($id);
            $this -> assign('data',$data);
            return $this -> fetch();
        }
        $result = $model->edit();

        if($result === false){
            $this->error($model->getError());
            return false;
        };
        $this -> success('修改成功');
    }
}


?>