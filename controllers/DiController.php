<?php 
namespace app\controllers;

use yii\web\Controller;
use app\common\Test;
use app\common\Test1;
use app\common\First;

use yii\di\Container;

/**
  * 
  */
 class DiController extends Controller
 {
 	
 	public function actionIndex(){

 		//依赖注入 start
 		echo "依赖注入<br>";
 		$test = new Test();
 		$first = new First();
 		$first->setTest($test);
 		$first->name = 'allen';
 		$first->hello();
 		//依赖注入 end 

 		echo "<br>容器方式<br>";
 		// test test1 两个类
 		$container = new Container();
 		$container->set('ff',[
 			'class' => 'app\common\First',
 			'name' => 'belle',
 			//'test' => new Test(),  // 或者下面的方式
 			'test' =>[
 				'class' => 'app\common\Test1'
 			] 
 		]);

 		$container->get('ff')->hello();

 		// 这个容器里依赖是我自己写的，感觉用的不对，但又不知怎么使用yii来解决这个依赖
 	}


 } 