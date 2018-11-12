<?php 
namespace app\index\model;
use think\Model;
/**
* 
*/
class User extends Model
{

	public function getClassIdAttr($value)
	{
		$data = [
			'1'=>'php',
			'2'=>'java',
			'3'=>'python'

		];
		return $data[$value];
	}
}

?>