<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2019/6/5
 * Time: 14:52
 */
namespace  app\assets;



class MyAsset extends  \yii\web\AssetBundle{

    public  $basePath = '@webroot';

    public  $css = [

    ];

    public  $js = [
        'js/my.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];


}