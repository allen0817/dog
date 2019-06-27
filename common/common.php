<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2019/6/12
 * Time: 17:22
 */

function p($data,$json=false){
	echo "<pre>";
	if($json){
		$data = json_decode($data,true);
	}
	print_r($data);
	//die;
}