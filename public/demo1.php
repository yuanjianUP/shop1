<?php 
class Human{
	public $obj;
	public function __construct($obj)
	{
		$this->obj=$obj;
	}
	public function drive()
	{
		$this->obj->start();
		echo 'drive';
	}
}
class Bus{
	public function start()
	{
		echo 'cart start';
	}
}
class Car{
	public function start()
	{
		echo 'cart start';
	}
}
$obj = new Bus();
$lisi = new Human($obj);
$lisi->drive();
?>