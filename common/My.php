<?php
namespace app\common;

use yii\base\BaseObject;

class My extends BaseObject
{
	public $_name;

	public function getName(){
		return $this->name;
	}
	public function setName($val){
		return $this->name = ucwords($val); //ucfirst($val);
//		 return $this->name = md5($val);
	}
}