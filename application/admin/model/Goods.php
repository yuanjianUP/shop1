<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/15
 * Time: 23:36
 */

namespace app\admin\model;


use think\migration\command\seed\Run;
use think\Model;
use think\Db;
class Goods extends Model
{
    protected $field = true;
    public function addGoods(){
        $data = input();
        $validate =  validate('Goods');
        if(!$validate -> check($data)){
            $this -> error = $validate->getError();
            return false;
        };
        //验证货号
        if($this -> checkSn($data)===false){
            $this -> error = '商品货号唯一';
            return false;
        }
        if($this -> uploadImg($data) === false){
            return false;
        };
        $data['addtime'] = time();
//        try{
            Goods::allowField(true)->save($data);
            $goods_id = Goods::getLastInsId();
            $list = $this -> insertAttr($goods_id,input('attr/a'));
            $this -> updateImgs($goods_id);
//        }catch (\Exception $e){
//            $this -> error = "写入异常";
//            return false;
//        }

    }
    //插入属性
    public function insertAttr($goods_id,$arr){
        $list = [];
        foreach ($arr as $key => $value){
            $value = array_unique($value);//去除重复的值
            foreach ($value as $vo){
                $list[] = [
                    'goods_id'=>$goods_id,
                    'attr_id'=>$key,
                    'attr_value'=>$vo
                ];
            }
        }
        if($list){
            Db::name('goods_attr')->insertAll($list);
        }
    }

    //验证商品号
    public function checkSn(&$data,$isEdit=false){
        if(empty($data['goods_sn'])){
            $data['goods_sn'] = uniqid();
        }
        $map = ['goods_sn'=>$data['goods_sn']];
        if($isEdit){
            $map['id'] = ['neq',$data['goods_id']];
        }
        if(Goods::get($map)){
           return false;
        };

    }
    //批量处理图片
    public function updateImgs($goods_id){
        $list = [];
        $files = request()->file('pic');
        if($files){
            foreach ($files as $file){
                $info = $file->validate(['ext'=>'jpg,png'])->move('uploads');
                if(!$info){
                    return False;
                }
                $goods_img = str_replace('\\','/','uploads/'.$info->getSaveName());
                $goods_thumb = 'uploads/'.date('Ymd').'/thumb_'.$info->getFileName();
//                dump($file->getSaveName());exit;
                $image = \think\Image::open($goods_img);

                $image->crop(150, 150)->save($goods_thumb);
                $list[] = [
                    'goods_id'=>$goods_id,
                    'goods_img'=>$goods_img,
                    'goods_thumb'=>$goods_thumb,
                ];
            }
            if($list){
                Db::name('goods_img')->insertAll($list);
            }
        }
    }
    //上传图片
    public function uploadImg(&$data){
        //上传图片
        $file = request()->file('goods_img');
        if($file === null){
            $this -> error = '图片必须上传';
            return false;
        }
        $info = $file -> validate(['ext','jpg,png'])->move('uploads');
        if(!$info){
            $this -> error = $info->getError();
            return false;
        }
        $data['goods_img'] = str_replace('\\','/','uploads/'.$info->getSaveName());
        //生成缩略图
//        dump($data['goods_img']);exit;
//        dump(file_exists($data['goods_img']));exit;
        $img = \think\Image::open($data['goods_img']);
        $data['goods_thumb'] = 'uploads/'.date('Ymd').'/thumb_'.$info->getFilename();
        $img -> thumb(150,150) -> save($data['goods_thumb']);
    }

    //获取商品列表
    public function getList(){
        $cat_id = input('cat_id/d',0);
        $where['is_delete'] = 1;
        //分类
        if($cat_id){
           $model = model('category');
           $cate = $model -> getCate(null,$cat_id);
//           dump($cate);exit;
           foreach ($cate as $key=>$value){
                $list []= $value['id'];
           }
           $list[] = $cat_id;
            $where['cate_id'] = ['in',$list];
        }
        $intro_type = input('intro_type');
        if($intro_type){
            $where[$intro_type] = 1;
        }
        //是否上架
        $is_sale = input('is_sale');
        if($is_sale){
            $where['is_sale'] = $is_sale;
        }
        //关键词
        $keyword = input('keyword');
        if($keyword){
            $where['goods_name'] = ['like','%'.$keyword.'%'];
        }
        return Db::name('goods')->where($where)->paginate(2,false,['query'=>input()]);
    }
    public function getGoodsById($id){
        return Goods::get($id);
    }
//改变商品的状态
    public function changeStatus(){
        $data = input();
//        dump($data['type']);exit;
        $arr = ['is_sale','is_rec','is_new','is_hot'];
        if(!in_array($data['type'],$arr)){
            $this -> error = '错误参数';
            return false;
        }
        $goodsData = Goods::get($data['goods_id']);
        $status = $goodsData[$data['type']]?0:1;
        $result = $this -> where(['id'=>$data['goods_id']]) -> setField($data['type'],$status);
        if(!$result){
            $this -> error = '修改失败';
            return false;
        }
        if($status == 0){
            return 2;//no.gif
        }else{
            return 1;//yes.gif
        }
    }
    //伪删除

    /**
     * @param $model Model
     * @param $id
     */
    public function del($model,$id){
        return $model -> where(['id'=>$id])->setField(['is_delete'=>2]);
    }

    public function edit(){
        $data = input();
        $validate = validate('Goods');
        if(!$validate->check($data)){
            $this -> error = $validate->getError();
            return false;
        }
        if($this -> checkSn($data,true) === false){
            $this -> error = "商品货号不能重复";
            return false;
        }
        Db::startTrans();
        try{
            Goods::allowField(true)->isUpdate(true)->save($data,['id'=>$data['goods_id']]);
            //属性先删除
            Db::name('goods_attr') -> where('goods_id',$data['goods_id'])->delete();
            $this -> insertAttr($data['goods_id'],input('attr/a'));
            Db::commit();
        }catch (\Exception $e){
            $this -> error = "修改异常".$e->getMessage();
            Db::rollback();
            return false;
        }


    }
}