<?php 
namespace app\admin\controller;
use think\Controller;
use think\Request;

/**
* 公共控制器
*/
class Common extends Controller
{
    public $_user = [];
    public $is_check_rule = true;
	public function __construct()
    {
        parent::__construct();
        if(!session('userid')){
            $this -> error('非法登录','admin/login/login');
        }
        $this -> _user['user'] = session("userid");
        $role_info = db('admin_role')->where(['admin_id'=>$this->_user['user']])->find();
        if(!$role_info){
            $this -> error('角色信息错误','admin/login/login');
        }
        $this->_user['role_id'] = $role_info['role_id'];
        $rule = [];
        if($this->_user['role_id']==1){
            $this -> is_check_rule = false;
            $rules = db('rule')->select();
        }else{
            $rule_list = db('role_rule')->where(['role_id'=>$this->_user['role_id']])->select();
            foreach ($rule_list as $key=>$value){
                $rule_ids[] = $value['rule_id'];
            }
            $rules = db('rule')->where(['id'=>['in',$rule_ids]])->select();
        }
        foreach ($rules as $key=>$value){
            $this -> _user['rules'][] = strtolowers($value['controller_name'].'/'.$value['action_name']);
            if($value['is_show']==1){
                $this -> _user['menu'][] = $value;
            }
        }
        if($this->is_check_rule){
            $this -> _user['rules'][]='index/index';
            $this -> _user['rules'][]='index/top';
            $this -> _user['rules'][]='index/menu';
            $this -> _user['rules'][]='index/main';
            $action = strtolowers(request()->controller().'/'.request()->action());
            if(!in_array($action,$this->_user['rules'])){
                $this -> error('您没有权限','login/login');
            }
        }

    }

}

?>