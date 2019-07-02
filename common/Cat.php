<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2019/7/1
 * Time: 16:55
 */

namespace app\common;


use yii\base\Component;

class Cat extends  Component
{
	const  MAO_EVENT = 'mao';

	public function shout(){
		echo '猫：miao miao miao <br />';
		$this->trigger(self::MAO_EVENT);
	}

}