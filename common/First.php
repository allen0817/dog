<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2019/6/24
 * Time: 15:52
 */

namespace app\common;


class First extends  My
{
	public  $_test;
	public function setTest($val){
		if(is_object($val)) return $this->test = $val;
		if(is_array($val))  return $this->test = new $val['class'] ;
	}
	public function  getTest(){
		return $this->test;
	}
	public function hello(){
		$this->setName($this->name);
		$this->test->test();
		return printf('hello %s',$this->name);
	}
}