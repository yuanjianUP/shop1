<?php 
// 1、申明命名空间  app是由于TP框架在载入的过程中将application目录所对应的命名空间进行替换 替换的结果为app
namespace app\index\controller;
use think\Config;
use think\Request;
use think\Db;
use \app\index\model\User;
class Stu{
	public $age =20;
	public function say()
	{
		echo 'hello';
	}
}
class Goods extends Common
{
	public function test7()
	{
		// 常规方式
		// $model = new User();
		// 使用助手函数 使用助手函数在当前模块下找不到模型类会使用common模块下的模型
		$model = model('User');
		dump($model);

	}
	public function modelfind()
	{
		$model = model('User');
		// 获取id为10的数据
		$data = $model->get(10);
		dump($data);
		// 获取多条数据
		$data = $model->all([10,11]);
		dump($data);
	}
	public function modelfind2()
	{
		$model = model('User');
		dump($model);
		echo '<hr/>';
		// 获取id为10的数据
		$data = $model->get(['name'=>'leo','class'=>'php']);
		dump($data);
	}
	public function modelfind3()
	{
		$model = model('User');
		// 获取id为10的数据
		$data = $model->get(function($query){
			return $query->where('id','>',10)->where('id','<',100);
		});
		// 模型基类下没有getLastSql方法存在但是存储__call魔术方法会进行转换，使用query对象调用下面的getLastSql方法执行
		echo $model->getLastSql();

		dump($data);
	}
	public function modelfind4()
	{
		$model = model('User');
		// 获取id为10的数据
		$data = $model->where('id','between','1,10')->select();
		dump($data);
	}
	public function modelfind5()
	{
		$model = model('User');
		// 获取id为10的数据
		// 使用toArray注意调用者需要为模型对象才可以使用
		// all返回的数组格式不能调用toArray 但是all返回的结果中每一个元素是可以调用toArray
		$data = $model->get(10)->toArray();
		dump($data);
		$data = $model->all([10,11]);
		foreach ($data as $key => $value) {
			dump($value->toArray());
		}
	}
	public function modelfind6()
	{
		$model = model('User');
		$data = $model->all([10,11]);
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function modelfind7()
	{
		$model = model('User');
		$data = $model->get(9);
		echo $data->class_id;
	}
	public function modeladd()
	{
		$model = model('User');
		$data = $model->save(['name'=>'leo8']);
		var_dump($data);
	}
	public function modeladd2()
	{
		$model = model('User');
		// 通过魔术方法设置$data属性中的内容
		$model->name='leo9';
		$model->class='java';
		$data = $model->save();
		var_dump($data);
	}
	public function modelupdate()
	{
		$model = model('User');
		// 通过魔术方法设置$data属性中的内容
		$model->name='leo9';
		$model->class='java';
		$data = $model->isUpdate(true,['id'=>10])->save();
		var_dump($data);
	}
	public function modelupdate2()
	{
		$model = model('User');
		$data = $model->isUpdate(true)->save(['name'=>'php11'],['id'=>10]);
		var_dump($data);
	}

	public function modeldel()
	{
		$model = model('User');
		dump($model->destroy(10));
		dump($model->destroy(['name'=>'leo']));
	}

	public function shiwu()
	{
		// 开启事物
		Db::startTrans();
		try{
			Db::name('classroom')->insert(['classroom'=>8]);
			Db::execute("insert into test (name) value('abcdefghy')");
			// 执行成功提交事物
			Db::commit();
		}catch(\Exception $e){
			Db::rollback();//回滚事物
			echo 'fail';
		}
	}
	public function sql()
	{
		// 获取到query对象
		$obj = Db::name('user');
		// 查询数据
		$sql = "select * from test_user where id=10";
		$data = $obj->query($sql);
		dump($data);
		// 写入数据
		$sql = "insert into test_user values(null,?,?,?)";
		$result = $obj->execute($sql,['leo6','go','3']);
		dump($result);
	}
	public function join()
	{
		// 获取到query对象
		$obj = Db::name('user');
		$data = $obj->alias('a')->field('a.*,b.classroom')->join('test_classroom b','a.class_id=b.id','left')->where('a.id>10')->select();
		echo $obj->getLastSql();
		dump($data);
	}
	public function tongji()
	{
		// 获取到query对象
		$obj = Db::name('user');
		// 求数据总的行数
		echo '数据总行数'.$obj->where('id','>',2)->count().'<hr/>';
		// 求数据最大值
		echo '数据最大值'.$obj->where('id','>',2)->max('id').'<hr/>';
		// 求合
		echo '数据最大值'.$obj->where('id','>',2)->sum('id').'<hr/>';
	}
	public function testwhere3()
	{
		// 获取到query对象
		$obj = Db::name('user');
		$obj->where('id','=',10)->select();
		echo $obj->getLastSql().'<hr/>';

		$obj->where('id','in','1,2,3')->select();
		echo $obj->getLastSql().'<hr/>';

		$obj->where('id','in',[1,2,3])->select();
		echo $obj->getLastSql().'<hr/>';
		// 闭包使用 主要用于设置复杂的SQL语句
		$obj->where(function($query){
			$query->where('id>10 and id<100');
		})->whereOr('class="1302"')->select();
		echo $obj->getLastSql().'<hr/>';
	}
	public function testwhere2()
	{
		// 获取到query对象
		$obj = Db::name('user');
		// 使用where数组
		$obj->where(['name'=>'php10'])->select();
		echo $obj->getLastSql().'<hr/>';
		// 使用where表示and关系
		$obj->where(['name'=>'php10','class'=>'1302'])->select();
		echo $obj->getLastSql().'<hr/>';
		// 使用where查询区间
		$obj->where(['id'=>['gt',10],'class'=>1301])->select();
		echo $obj->getLastSql().'<hr/>';
		// where以及whereOR可以多次调用组装条件 3.2版本中只能调用一次
		$obj->where(['id'=>10])->where(['name'=>'php11'])->whereOr(['class'=>1301])->select();
		echo $obj->getLastSql().'<hr/>';
	}
	public function testwhere()
	{
		// 获取到query对象
		$obj = Db::name('user');
		// 使用where字符串方式与原生的SQL语句一致
		$obj->where('name="php10" and class="1301"')->select();
		echo $obj->getLastSql().'<hr/>';
		// where配合bind方法  在where中所指定的:name只是占位符，会使用bind中所绑定的name下标对应的内容将占位符替换成为数据
		$obj->where('name=:name')->bind(['name'=>'php10'])->select();
		echo $obj->getLastSql().'<hr/>';
	}
	public function lianguan()
	{
		// 获取到query对象
		$obj = Db::name('user');
		// 使用alias取别名
		$obj->alias('a')->find();
		echo $obj->getLastSql().'<hr/>';
		// 使用field设置需要的字段信息  格式与原生SQL语句中select与from之间的一摸一样
		$obj->alias('a')->field('a.name,a.class')->find();
		echo $obj->getLastSql().'<hr/>';
		// limit使用
		$obj->alias('a')->field('a.name,a.class')->limit(2)->select();
		echo $obj->getLastSql().'<hr/>';
		$obj->alias('a')->field('a.name,a.class')->limit(1,2)->select();
		echo $obj->getLastSql().'<hr/>';
		// page实现分页 第一个参数设置页码 第二个参数设置页尺寸
		$obj->alias('a')->field('a.name,a.class')->page(3,2)->select();
		echo $obj->getLastSql().'<hr/>';
		// order排序  order方法中的参数与原生SQL语句中的order by 一致
		$obj->alias('a')->field('a.name,a.class')->page(3,2)->order('a.id desc')->select();
		echo $obj->getLastSql().'<hr/>';
		// fetchSql获取组装的SQL语句 
		echo $obj->alias('a')->field('a.name,a.class,a.username')->fetchSql(TRUE)->page(3,2)->order('a.id desc')->select();
	}
	public function testforeach()
	{
		$data = [
			['id'=>1,'cname'=>'手机','parent_id'=>0],
			['id'=>2,'cname'=>'电脑','parent_id'=>0],
			['id'=>3,'cname'=>'衬衣','parent_id'=>0],
		];
		$this->assign('datas',[]);
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function testif()
	{
		$this->assign('data',['status'=>1]);
		$this->assign('value',[1,2,3]);
		// status 表示状态 1,2,3值代表的信息已经规定
		$this->assign('status',1);
		return $this->fetch();
	}
	public function yunsuan()
	{
		$this->assign('a',10);
		$this->assign('b',2);
		return $this->fetch();
	}
	public function gsh()
	{
		// 代表从数据库查询的内容
		$data = [
			['name'=>'a','time'=>time()],
		];
		$this->assign('url','index/index/index');
		$this->assign('string','abcdefjkykjjk');
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function system()
	{
		return $this->fetch();
	}

	public function replace()
	{
		return $this->fetch();
	}

	public function fuzhi6()
	{
		$obj = new Stu();
		$this->assign('obj',$obj);
		return $this->fetch();
	}
	public function fuzhi()
	{
		$data = 'abcde';//代表控制器中所运算的数据
		// 第一个参数为模板中所使用的变量名称
		// 第二个参数为数据本身
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function fuzhi2()
	{
		$data = 'abcde';//代表控制器中所运算的数据
		
		return $this->fetch('fuzhi',['data'=>$data]);
	}

	public function fuzhi3()
	{
		$string = 'string';
		$int = 20;
		$bollean = true;
		$float = 1.8;
		return $this->fetch('fuzhi3',['string'=>$string,'int'=>$int,'bollean'=>$bollean,'float'=>$float]);
	}
	public function fuzhi5()
	{
		$data = ['string'=>'string','int'=>20];
		return $this->fetch('fuzhi5',['data'=>$data]);
	}

	public function getInfo2(Request $request)
	{
		// 接受get中的name参数
		dump($request->param('name'));
		dump(input('get.name'));
		// 省略请求方式的名称 使用默认的param方式获取数据
		dump(input('name'));
		// 接受所有的get参数
		dump(input('get.'));
	}

	public function getInfo3(Request $request)
	{
		dump(input('age'));
		// 获取数据并且使用修饰符
		dump(input('age/d'));
	}

	public function getInfo4(Request $request)
	{
		dump(input('age',20));
	}
	
	public function getInfo5(Request $request)
	{
		// 第三个参数可以使用TP内置的公共函数、PHP内置的公共函数或者自定义的公共函数进行过滤
		echo input('name','','htmlspecialchars,strtoupper,strtolowers');
	}

	public function getInfo(Request $request)
	{
		// 获取当前的模块名称
		echo '当前模块名称'.$request->module().'<hr/>';
		// 获取当前控制器名称
		echo '当前控制器名称'.$request->controller().'<hr/>';
		// 获取当前方法名称
		echo '当前方法名称'.$request->action().'<hr/>';
		// 获取pathinfo信息
		echo '当前pathinfo名称'.$request->pathinfo().'<hr/>';
	}
	public function methods(Request $request)
	{
		// 判断是否为get方式的请求
		dump($request->isGet());
		// 判断是否为Post方式的请求
		dump($request->isPost());
	}
	public function getObj(Request $obj)
	{
		// $obj = Request::instance();
		// $obj=request();
		dump($obj);
	}
	public function testS()
	{
		// 写入数据
		$this->success('写入成功','index');
	}
	public function testR()
	{
		// 重定向
		$this->redirect('index');
	}
	public function index()
	{
		// 推荐使用return输出返回的结果，虽然echo是输出由于return返回的结果TP可以再次处理
		return 'goods controller index action';
	}
	// 临时设置配置信息
	public function setConfig()
	{
		Config::set('web_name','京东商城');
		dump(\think\Config::get('web_name'));
	}
	// 读取配置信息
	public function getConfig()
	{
		// dump助手函数是用于查看数据 等价于 echo '<pre>' var_dump()
		dump(\think\Config::get('web_name'));
	}

	// 使用助手函数操作动态配置
	public function useHelperConfig()
	{
		// 使用助手函数 无须载入相关的文件
		// 使用config设置配置项传递两个参数
		config('web_name','京东商城');
		// 读取所指定的配置名称
		dump(config('web_name'));
		// 读取所有的配置信息
		dump(config());
	}
	public function getConfig2()
	{
		// 读取cache配置项下的type
		dump(config('cache.type'));
		dump(config());
	}

	public function getConfig3()
	{
		dump(config('web_name_2'));
	}
}


?>