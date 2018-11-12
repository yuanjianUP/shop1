<?php 
namespace app\admin\controller;
use think\Controller;
use think\Request;

/**
* 公共控制器
*/
class Common extends Controller
{
	public function __construct()
    {
        parent::__construct();
        if(!session('userid')){
            $this -> error('非法登录','admin/login/login');
        }
    }

}

?>