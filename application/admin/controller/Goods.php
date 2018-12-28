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
            $type = Db::name('type')->select();
            $this -> assign('type',$type);
            $cateModel = model('category');
            $cate = $cateModel->getCate();
            $this -> assign('cate',$cate);
            return $this -> fetch();
        }
//        dump(request()->file('pic'));exit;
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
        $cate = $cateModel -> getCate(null,0,true);
        $this -> assign('cate',$cate);
	    $this -> assign('data',$data);
	    return $this -> fetch();
    }

    public function edit(){
        $model = model('Goods');
        $id = input('goods_id');
        if(request()->isGet()){
            $type = Db::name('type')->select();
            $this -> assign('type',$type);
            $cate = model('category')->getCate();
            $this -> assign('cate',$cate);
            $data = $model -> getGoodsById($id);
//            $data = $data->toArray();
            $this -> assign('data',$data);
            $attr = model('Attribute')->getGoodsA($id);
            $this -> assign('attr',$attr);
            $imgs = Db::name('goods_img')->where(['goods_id'=>$id])->select();
            $this -> assign('imgs',$imgs);
            return $this -> fetch();
        }

        $result = $model->edit();
//        dump($result);exit;
        if($result === false){
            $this->error($model->getError());
            return false;
        };
        $this -> success('修改成功');
    }
   //改变商品状态
    public function changeStatus(){
        $model = model('goods');
        $result = $model -> changeStatus();
        if(!$result){
            ajaxReturn($model->getError(),0);
        }
        if($result == 1){
            ajaxReturn('1',1);
        }else{
            ajaxReturn('2',2);
        }
    }
    //删除商品
    public function del(){
        $model = model('goods');
        $id = input('goods_id');
        $result = $model -> del($model,$id);
        if(!$result){
            $this ->error('删除失败');
        }
        $this -> success('成功');
    }

    public function showAttr(){
	    $type_id = input('type_id',0,'intval');
	    if($type_id<=0){
            return '选择合适的类型';
        }
        $data = model('attribute')->getType($type_id);
//	    dump($data);exit;
	    $this -> assign('data',$data);
	    return $this -> fetch();
    }
}


