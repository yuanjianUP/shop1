<?php
/**
 * Created by PhpStorm.
 * User: jian
 * Date: 2018/11/15
 * Time: 23:36
 */

namespace app\admin\model;


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
        Goods::allowField(true)->save($data);

    }

    //验证商品号
    public function checkSn(&$data){
        if(empty($data['goods_sn'])){
            $data['goods_sn'] = uniqid();
        }else{
            if(Goods::get(['goods_sn'=>$data['goods_sn']])){
               return false;
            };
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

    public function getList(){
        $cat_id = input('cat_id/d',0);
        $where['is_delete'] = 1;
        if($cat_id){
           $model = model('category');
           $cate = $model -> getCate($cat_id);
//           dump($cate);exit;
           foreach ($cate as $key=>$value){
                $list []= $value['id'];
           }
           $list[] = $cat_id;
            $where['cate_id'] = ['in',$list];
        }
//        dump($where);exit;
        return Db::name('goods')->where($where)->paginate(2,false,['query'=>input()]);
//       return Goods::alias('g')
//           ->join('shop_category c','g.cate_id=c.id','left')
//           ->field('g.*,c.name')
//           ->where($where)
//           ->paginate(2);
    }
    public function getGoodsById($id){
        return Goods::get($id)->paginate(2);
    }
}