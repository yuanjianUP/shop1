<?php
use think\Route;
// 实现用户访问http://tp5.com/trace最终执行的是现有的http://tp5.com/admin/Goods/testtrace
// Route::rule('trace','admin/Goods/testtrace');
// 路由携带参数
// Route::rule('trace/:id','admin/Goods/trace');
// 路由的可选参数   可选参数存在的位置一定在最后
// Route::rule('trace/:id/[:type_id]','admin/Goods/trace');
// 完全匹配 在最后加上$符号即可
// Route::rule('trace$','admin/Goods/trace');
// 额外参数
// Route::rule('trace$','admin/Goods/trace?id=100');
// 请求类型的控制
// 第三个参数如果想支持多种方式可以使用"GET|POST",如果不限制请求方式"*"
// Route::rule('trace','admin/Goods/trace','POST');
// 使用简化方法控制访问方式
// Route::post('trace','admin/Goods/trace');
// 路由参数使用
// Route::rule('trace','admin/Goods/trace','*',['ext'=>'html']);
// 变量规则
// Route::rule('trace/:id','admin/Goods/trace','*',[],['id'=>'\d+']);
// 闭包支持
// Route::rule('trace',function(){
// 	return 'bibao';
// });
// 批量注册
// Route::rule([
// 	'trace'=>'admin/Goods/trace',
// 	'index'=>'admin/Goods/index'
// ]);
// return [
// 	'trace'=>'admin/Goods/trace',
// 	'index'=>'admin/Goods/index'
// ];