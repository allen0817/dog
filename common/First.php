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
	private  $_test;


	public function setTest($val){
		if(is_object($val)) return $this->_test = $val;
		if(is_array($val))  return $this->_test = new $val['class'] ;
	}
	public function  getTest(){
		return $this->_test;
	}
	public function hello(){
		$this->_test->test();
		return printf('hello %s',$this->name);
	}
}