<?php
namespace app\index\controller;
use think\Db;
use think\Session;
use think\Cookie;
class Index
{
	// 产生验证码图片
	public function yzm()
	{
		return view();
	}
	public function checkyzm()
	{
		$config = [
			'length'=>3,
			'codeSet'=>'1234567890',
		];
		$obj = new \think\captcha\Captcha($config);
		dump($obj->check(input('code')));
	}
	public function makeyzm()
	{
		$config = [
			'length'=>3,
			'codeSet'=>'1234567890',
		];
		$obj = new \think\captcha\Captcha($config);
		return $obj->entry();
	}
	public function index2()
	{
		$data = Db::name('goods')->select();
		
		return view('index',['data'=>$data]);
	}
	public function uploadOne()
	{
		if(request()->isGet()){
			return view();
		}
		// 获取对象
		$file = request()->file('image');
		// 上传文件  第一个参数指定上传的文件所保存的根目录
		$info = $file->validate(['ext'=>'jpg,gif'])->move('uploads');
		if($info){
			// 上传成功
			echo '上传文件保存目录及名称:'.$info->getSaveName().'<hr/>';
			echo '上传文件名称:'.$info->getFileName().'<hr/>';
			echo '文件的完整地址'.'uploads/'.$info->getSaveName();

		}else{
			// 获取到错误信息
			echo $file->getError();
		}
	}
	// // 设置session
	// public function setS()
	// {
	// 	Session::set('name','hello','abc');
	// }
	// // 读取session
	// public function getS()
	// {
	// 	dump(Session::get('name'));
	// 	dump($_SESSION);
	// }
	// // 删除session
	// public function delS()
	// {
	// 	Session::delete('name');
	// }
	// 设置session
	public function setS()
	{
		session('name','leo');
	}
	// 读取session
	public function getS()
	{
		dump(session('name'));
		dump($_SESSION);
	}
	// 删除session
	public function delS()
	{
		session('name',null);
	}

    // 设置cookie
	public function setC()
	{	
		Cookie::set('name2','leo2',3600);
		cookie('name','leo');
	}
	// 读取cookie
	public function getC()
	{
		dump(cookie('name'));
		dump(Cookie::get('name2'));
	}
	// 删除cookie
	public function delC()
	{
		cookie('name',null);
		Cookie::delete('name2');
	}
}
