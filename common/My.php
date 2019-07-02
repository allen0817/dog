<?php
namespace app\common;

use yii\base\BaseObject;

class My extends BaseObject
{
	private $_name;

	public function getName(){
		return $this->_name;
	}
	public function setName($val){
		return $this->_name = ucwords($val); //ucfirst($val);
//		 return $this->name = md5($val);
	}
}