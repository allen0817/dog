<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-form">

    <?= $form = Html::beginForm(
        $action = \yii\helpers\Url::to(['config/update','key'=>key($model->allModels)]),
        $method = 'post',
        $options = []
    );?>
    <div class="form-group">
        <?= Html::label('KEY',['class' => 'label-control']) ?>
        <?= Html::textInput('other',key($model->allModels),[ 'class' => 'form-control','disabled'=>'disabled'] ) ?>
        <?= Html::hiddenInput('key',key($model->allModels)) ?>
    </div>

    <div class="form-group">
        <?= Html::label('VALUE') ?>
        <?= Html::textarea('value',current($model->allModels),[ 'class' => 'form-control' ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>



    <?= Html::endForm(); ?>





</div>
