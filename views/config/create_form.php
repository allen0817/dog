<?php
use app\assets\MyAsset;

MyAsset::register($this);

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-form">

    <?= $form = Html::beginForm(
        $action = \yii\helpers\Url::to(['config/create']),
        $method = 'post',
        $options = []
    );?>
    <div class="form-group">
        <?= Html::label('KEY',['class' => 'label-control']) ?>
        <?= Html::textInput('key','',[ 'class' => 'form-control config_field','required'=>'required','id' => 'config_key' ] ) ?>
    </div>

    <div class="form-group">
        <?= Html::label('VALUE') ?>
        <?= Html::textarea('value','',[ 'class' => 'form-control config_field','required'=>'required' ,'id'=>'config_value' ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>



    <?= Html::endForm(); ?>





</div>
